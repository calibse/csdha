import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;
window.axios.defaults.validateStatus = function (status) {
	return (status >= 200 && status < 300) || status === 404 || 
            status === 403;
};
