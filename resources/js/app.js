import './bootstrap';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect'
import mask from '@alpinejs/mask'
import persist from '@alpinejs/persist'
import Swal from 'sweetalert2';
import Dropzone from 'dropzone'


Alpine.plugin(intersect);
Alpine.plugin(mask);
Alpine.plugin(persist);

window.Alpine = Alpine;

window.Alpine.start();

window.Swal = Swal;
// window.Dropzone = Dropzone;