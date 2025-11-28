const verticalCardInGrid = document.querySelector('.col-1 > div');
const mediaQueryToGrid = window.matchMedia('screen and (max-width: 974px)');
const changeTheVerticalCardInGrid = (event) => {
    if (event.matches) {
        // console.log('Медиазапрос работает');
        verticalCardInGrid.classList.toggle('vertical', false);
    } else {
        // console.log('Медиазапрос не работает');
        verticalCardInGrid.classList.toggle('vertical', true);
    }
}
mediaQueryToGrid.addEventListener('change', changeTheVerticalCardInGrid);
changeTheVerticalCardInGrid(mediaQueryToGrid);

