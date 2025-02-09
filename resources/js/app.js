import "./bootstrap";
import Swal from "sweetalert2";

window.Swal = Swal.mixin({
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});
