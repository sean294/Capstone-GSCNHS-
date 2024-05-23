console.log('Script loaded');
document.getElementById('qr').addEventListener('click', function(){
    document.querySelector('.qr-modal').style.display='flex';
});
document.getElementById('qr-close').addEventListener('click', function(){
    document.querySelector('.qr-modal').style.display='none';
});