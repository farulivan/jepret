function selectImage(event) {
    event.preventDefault();
    const imageFileInput = document.getElementById("imageFileInput");
    imageFileInput.click();
}

function openModalCreatePost() {
    // check whether FileReader is supported on client browser
    if (!FileReader) {
        alert("File Reader is not supported!")
        return
    }

    // set file content to preview container
    const imageFileInput = document.getElementById("imageFileInput");
    const fr = new FileReader();
    fr.onload = function() {
        document.getElementById("selectedImage").src = fr.result;
    }
    fr.readAsDataURL(imageFileInput.files[0]);

    // display modal
    const modalCreatePost = document.getElementById("modalCreatePost");
    modalCreatePost.setAttribute("open", true);

    // set focus on image caption
    document.getElementById('imageCaption').focus();
}

function closeModalCreatePost(event) {
    event.preventDefault();

    // hide modal
    const modalCreatePost = document.getElementById("modalCreatePost");
    modalCreatePost.removeAttribute("open");

    // reset form values
    const formCreatePost = document.getElementById("formCreatePost");
    formCreatePost.reset();
}

document.addEventListener('DOMContentLoaded', function(){
    const imageFileInput = document.getElementById("imageFileInput");
    imageFileInput.onchange = openModalCreatePost;

    const formCreatePost = document.getElementById("formCreatePost");
    formCreatePost.onsubmit = closeModalCreatePost;

    const btnCancelCreatePost = document.getElementById("btnCancelCreatePost");
    btnCancelCreatePost.onclick = closeModalCreatePost;

    const btnSelectImage = document.getElementById("btnSelectImage");
    btnSelectImage.onclick = selectImage;
});
