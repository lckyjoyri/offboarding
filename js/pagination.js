function paginateTable(tableID, data, rowsPerPage, paginationContainerID = null) {
    const table = document.getElementById(tableID);
    const tbody = table.querySelector("tbody");
    const rows = tbody.getElementsByTagName("tr");
    
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    // Handle edge case if no rows in table
    if (totalRows === 0) return;

    // Initialize pagination controls
    const paginationContainer = paginationContainerID ? document.getElementById(paginationContainerID) : document.getElementById('pagination');
    paginationContainer.innerHTML = '';

    // Create "Previous" button
    const prevButton = document.createElement('li');
    prevButton.classList.add('page-item');
    prevButton.innerHTML = `<a class="page-link" href="#" onclick="goToPage('prev', ${totalPages}, ${rowsPerPage})"><i class="bi bi-chevron-left"></i></a>`;
    paginationContainer.appendChild(prevButton);

    // Create page number buttons
    for (let page = 1; page <= totalPages; page++) {
        const pageButton = document.createElement('li');
        pageButton.classList.add('page-item');
        pageButton.innerHTML = `<a class="page-link" href="#" onclick="goToPage(${page}, ${totalPages}, ${rowsPerPage})">${page}</a>`;
        paginationContainer.appendChild(pageButton);
    }

    // Create "Next" button
    const nextButton = document.createElement('li');
    nextButton.classList.add('page-item');
    nextButton.innerHTML = `<a class="page-link" href="#" onclick="goToPage('next', ${totalPages}, ${rowsPerPage})"><i class="bi bi-chevron-right"></i></a>`;
    paginationContainer.appendChild(nextButton);

    // Initialize the table rows based on the current page
    let currentPage = 1;
    function updateTablePage(page) {
        currentPage = page;
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        for (let i = 0; i < totalRows; i++) {
            rows[i].style.display = i >= start && i < end ? '' : 'none';
        }
    }

    // Function to handle page navigation (prev, next, or specific page)
    window.goToPage = function(page, totalPages, rowsPerPage) {
        if (page === 'prev') {
            if (currentPage > 1) currentPage--;
        } else if (page === 'next') {
            if (currentPage < totalPages) currentPage++;
        } else {
            currentPage = page;
        }
        updateTablePage(currentPage);
    }

    // Initialize table for the first page
    updateTablePage(currentPage);
}
