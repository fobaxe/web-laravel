// Charge Axios pour centraliser les requetes HTTP cote front.
import axios from 'axios';

// Expose Axios sur window pour l'utiliser facilement dans d'autres scripts.
window.axios = axios;

// Ajoute un en-tete standard pour identifier les requetes AJAX Laravel.
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
