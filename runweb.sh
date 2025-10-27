#! /bin/sh

set -e

dir="csdha"
asset_dir="csdha_assets"
files=".env.production .env.production-admin kube container web.sh" 
assets="build icon font images"

sync() {
	for file in $files
	do
		rsync -avz ${file} ${SSH_SERVER}:${dir}/
	done
}

sync_assets() {
	npm run build
	for asset in $assets
	do
		rclone sync public/${asset} dropbox:${asset_dir}/public/${asset}
	done
}

if [ $# -ge 1 ]
then
	func=$1
	if type "$func" 2>/dev/null | grep -q 'function'
	then
		$func 
	else
		ssh ${SSH_SERVER} "cd ${dir} && ./web.sh ${func}"
	fi
else
	ssh ${SSH_SERVER} "cd ${dir} && ./web.sh update"
fi

