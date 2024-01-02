// bubble sort: original -- https://www.w3schools.com/howto/howto_js_sort_list.asp

function sort(tableID, n, xRow) {
    var table, rows, switching, i, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById(tableID);
    switching = true;
    dir = 0;

    while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - xRow); i++) { // xRow is a number of rows excluded from sorting ("Celkem" rows)
            shouldSwitch = compareRows(rows[i], rows[i + 1], n, dir);

            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            }
        }

        if (switchcount === 0 && dir === 0) {
            dir = 1;
            switching = true;
        }
    }
}

function compareRows(rowA, rowB, n, dir) {
    var x = rowA.getElementsByTagName("TD")[n];
    var y = rowB.getElementsByTagName("TD")[n];

    var xVal = getValue(x);
    var yVal = getValue(y);

    //console.log(xVal);

    if (dir === 0) {
        return xVal > yVal;
    } else {
        return xVal < yVal;
    }
}

function getValue(element) {
    var rawValue = element.innerHTML.trim();

    if (!rawValue.match(/^\d{2}\s{2}\d+$/)) { // not a doc number
        if (rawValue.match(/\.{1}/g) && !rawValue.match(/^\d{2}\.\d{2}\.\d{4}$/)) { // float values, not a date
            var numValue = parseFloat(rawValue.replace(/,/g, ''));
            if (!isNaN(numValue)) {
                //console.log("číslo:", numValue);
                return numValue;
            }
        }
        var dateValue = parseDate(rawValue);
        if (!isNaN(dateValue)) {
            //console.log("datum:", dateValue);
            return dateValue;
        }
    }

    //console.log("string:", rawValue.toLowerCase());
    return rawValue.toLowerCase(); // other strings
}

function parseDate(dateString) {
    var parts = dateString.split('.');

    return new Date(parts[2], parts[1] - 1, parts[0]).getTime();
}
