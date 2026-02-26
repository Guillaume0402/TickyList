console.log('Coucou !');


document.querySelectorAll('.flash').forEach((el) => {
  setTimeout(() => el.remove(), 3000);
});