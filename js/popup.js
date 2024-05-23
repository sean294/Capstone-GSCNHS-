console.log('Script loaded');
document.getElementById('add-btn').addEventListener('click', function(){
    console.log('Button clicked');
    document.querySelector('.bg-modal').style.display='flex';
});
document.getElementById('close').addEventListener('click', function(){
    console.log('Button clicked close');
    document.querySelector('.bg-modal').style.display='none';
});
