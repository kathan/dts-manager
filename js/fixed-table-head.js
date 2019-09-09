fixedTableHead = (selector) => {
    const table = document.querySelectorAll(selector);
    if (table.length > 0) {
        const theadSelector = selector + ' thead';
        const thead = document.querySelectorAll(theadSelector);

    }
    var i = 1;
}

(() => {
    fixedTableHead('#load-list-table');
});