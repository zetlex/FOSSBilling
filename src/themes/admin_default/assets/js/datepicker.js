import Litepicker from 'litepicker';

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.datepicker').forEach(element => {
    element.autocomplete = 'off';
    new Litepicker({
      element: element,
      format: 'YYYY-MM-DD',
      dropdowns: {
        minYear: 1930,
        months: !!element.dataset.pickMonth,
        years: !!element.dataset.pickYear
      },
      singleMode: !isRanger(element),
      delimiter: ' to ',
      buttonText: {
        previousMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 6l-6 6l6 6"></path></svg>',
        nextMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 6l6 6l-6 6"></path></svg>',
      },
      setup: (picker) => {
        picker.on('selected', (date, dateTo) => {
          if (isRanger(element) && dateTo) {
            setDateToEl(element.dataset.nameFrom, date.format('YYYY-MM-DD'), element);
            setDateToEl(element.dataset.nameTo, dateTo.format('YYYY-MM-DD'), element);
          }
        });
      }
    });

  });
});

function isRanger(element) {
  return element.dataset.nameFrom && element.dataset.nameTo;
}

function setDateToEl(name, value, siblingEl = null) {
  let element = document.getElementById(name);
  if (element === null) {
    element = document.createElement('input');
    element.type = 'hidden';
    element.id = name;
    element.name = name;
    element.value = value;
    siblingEl.parentNode.insertBefore(element, siblingEl.nextSibling);
    return;
  }
  element.value = value;
}
