window.addEventListener('load', () => {
  let notePad = document.querySelector('#textNotePad');
  let btnNotePad = document.querySelector('#btnNotePad');
  btnNotePad.addEventListener('click', () => {
      if(notePad.style.display !== 'block'){
          btnNotePad.innerHTML = 'Désactiver';
          notePad.style.display = 'block';
      }
      else{
          btnNotePad.innerHTML = 'Activer';
          notePad.style.display = 'none'
      }
  })
});