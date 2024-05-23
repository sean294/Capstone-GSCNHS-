
function getImagePreview(event){
    let image = URL.createObjectURL(event.target.files[0]);
    let imageiv = document.getElementById('preview');
    let newimg=document.createElement('img');
    imageiv.innerHTML = '';
    newimg.src=image;
    newimg.width="120";
    newimg.height="100";
    imageiv.appendChild(newimg);
}