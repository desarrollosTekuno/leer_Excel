
const btnSubirArchivo = document.querySelector("#subirArchivo");
const adjunto = document.querySelector("#adjunto");
let allowedExtensions = ['xlsx', 'xlsm', 'xls'];
const fileValidation = (selector, extensions) => {
    let filePath = selector.value || '';
    // Seperar nombre de archivo por . y obtener último elemento (extensión)
    let extension = filePath.split('.').pop().toLowerCase();
    // Verificar que la extensión es permitida
    if (!extensions.includes(extension)) {
        return false;
    }
    return true;
}

adjunto.addEventListener("change", () => {
    if (fileValidation(adjunto, allowedExtensions) == true) {
        btnSubirArchivo.disabled = false;
    } else {
        Swal.fire({
            position: 'top',
            icon: 'warning',
            title: 'Porfavor suba archivos con una extensión válida',
            html: `<h5>Extensiones permitidas: ${allowedExtensions.join(', ')}</h5>`,
            showConfirmButton: false,
            timer: 5000
        })
        btnSubirArchivo.disabled = true;
    }
});

$("#frmFiles").on("submit", function (e) {
    const progressArea = document.querySelector("#progressArea");
    const uploadedArea = document.querySelector("#uploadedArea");
    const resultado = document.querySelector("#resultado");
    e.preventDefault();
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "loadFile.php");
    xhr.upload.addEventListener("progress", ({ loaded, total }) => {
        let fileLoaded = Math.floor((loaded / total) * 100);
        let fileTotal = Math.floor(total / 1000);
        let fileSize;
        (fileTotal < 1024) ? fileSize = `${fileTotal} KB` : fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB";
        //console.log(fileLoaded, fileTotal);
        let progressHTML = `
        <li class="row">
            <div class="content">
                <div class="details text-end">
                    <span class"percent">${fileLoaded}%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: ${fileLoaded}%" aria-valuenow="${fileLoaded}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </li>
        `;
        progressArea.innerHTML = progressHTML;
        if (loaded == total) {
            //progressArea.innerHTML = "";
            let uploadedHTML = `
            <li class="row">
                <div class="content">
                    <div class="details text-end">
                        <span class="size">${fileSize}</span>
                    </div>
                </div>
            </li>
            `;
            uploadedArea.innerHTML = uploadedHTML;
        }
    });
    let form = document.getElementById("frmFiles");
    let formData = new FormData(form);
    xhr.onreadystatechange = () => {
        // In local files, status is 0 upon success in Mozilla Firefox
        if (xhr.readyState === XMLHttpRequest.DONE) {
            const status = xhr.status;
            if (status === 0 || (status >= 200 && status < 400)) {
                // The request has been completed successfully
                const respuesta = JSON.parse(xhr.responseText);
                Swal.fire({
                    position: 'top',
                    icon: 'info',
                    html: `
                <div>
                    <table class="table table-stripped text-start">
                        <tbody>
                            <tr>
                                <th scope="row">Registros creados</th>
                                <td>${respuesta.creados}</td> 
                            </tr>
                            <tr>
                                <th scope="row">Registros encontrados</th>
                                <td>${respuesta.encontrados}</td> 
                            </tr>
                            <tr>
                                <th scope="row">Errores encontrados</th>
                                <td>${respuesta.errores}</td> 
                            </tr>
                            <tr>
                                <td><div><span class="fw-bold">Documentos validados:</span></div><ul id="checklist-documents">${respuesta.lista_checklist}</ul></td> 
                            </tr>
                        </tbody>
                    </table>
                </div>
                `,
                    showConfirmButton: true,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#172e5c',
                    //backdrop: false,
                    //timer: 1500
                }).then((result) => {
                    if (result.isConfirmed) {
                        progressArea.innerHTML = "";
                        uploadedArea.innerHTML = "";
                        form.reset();
                        $('#myModal').modal('hide');
                    }
                })
                //resultado.innerHTML= xhr.response;
                //console.log(xhr.response);
            } else {
                // Oh no! There has been an error with the request!
            }
        }
    };
    xhr.send(formData);
});