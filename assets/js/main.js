document.addEventListener('DOMContentLoaded',()=>{document.querySelectorAll('img').forEach(img=>{img.addEventListener('error',()=>{img.style.opacity=.35;});});});
