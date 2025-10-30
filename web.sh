#! /bin/sh

set -e

app="csdha"
containers="base:alpine-laravel cache:alpine-memcached server:alpine-apache init:laravel-init queue:laravel-queue sync:laravel-sync web:laravel-web"
init_kube="pvc.yaml secret.yaml database.yaml cache.yaml init.yaml server.yaml"
install_kube="queue.yaml web-admin.yaml web-user.yaml"
update_kube="sync.yaml"
volumes="web-app web-user-storage web-user-cache web-admin-storage web-admin-cache"
func=''
orig_ifs=''
entry=''
container_dir=''
container_name=''

parse_entry() {
	entry=$1
	orig_ifs=$IFS
	IFS=':'
	read -r container_dir container_name <<EOF
$entry
EOF
	IFS=$orig_ifs
}

build() {
	for container in $containers
	do
		parse_entry $container
		podman build -f container/${container_dir}/Dockerfile -t ${container_name}:1.0 .
	done
}

init() {
	for kube in $init_kube
	do
		podman kube play kube/${kube}
	done
}

restart_queue() {
	podman exec ${app}-queue-pod-queue php artisan queue:restart
	set +e
	podman wait ${app}-queue-pod-queue
	set -e
}

uninstall() {
	restart_queue
	for kube in $install_kube
	do
		podman kube down kube/${kube}
	done
	podman kube down kube/${update_kube}
	for kube in $init_kube
	do
		podman kube down kube/${kube}
	done
	for volume in $volumes
	do
		podman volume rm csdha-${volume}
	done
}

reinstall() {
	restart_queue
	for kube in $install_kube
	do
		podman kube play --replace kube/${kube}
	done
	podman kube play --replace --start=false kube/${update_kube}
}

install() {
	for kube in $install_kube
	do
		podman kube play kube/${kube}
	done
	podman kube play --start=false kube/${update_kube}
	podman image prune -f
}

update() {
	restart_queue
	podman start ${app}-queue-pod-queue
	podman start ${app}-sync-pod-sync
}

if [ $# -ge 1 ]
then
	func=$1
	if type "$func" 2>/dev/null | grep -q 'function'
	then
		$func 
	else
		echo "Function '$func' not found" >&2
	fi
else
	echo "Usage: $0 <function>"
fi

