// ログアウトボタンの操作
(function () {
    let logout = document.getElementById('root-navigation-logout')
    logout.onclick = function () {
        fetch('../Backend/Api/AuthorizerLogoutApi.php')
            .then(response => response.json())

        window.location.href = './AuthorizerLogin.html';
    }
}());

// 先月ボタンの操作
(function () {
    let lastMonth = document.getElementById('root-menu-lastMonth')
    let thisMonth = document.getElementById('root-menu-thisMonth')
    lastMonth.onclick = function () {
        let lastMonthNumber = parseInt(/^([0-9]*)月$/.exec(thisMonth.innerText)[1]) - 1
        if (String(lastMonthNumber).length === 1) {
            lastMonthNumber = '0' + lastMonthNumber
        }
        window.location.href = `./EmployeeList.php?thisMonth=${lastMonthNumber}`;
    }
    if (thisMonth.innerText === '1月') {
        lastMonth.remove()
    }
}());

// 来月ボタンの操作
(function () {
    let nextMonth = document.getElementById('root-menu-nextMonth')
    let thisMonth = document.getElementById('root-menu-thisMonth')
    nextMonth.onclick = function () {
        let nextMonthNumber = parseInt(/^([0-9]*)月$/.exec(thisMonth.innerText)[1]) + 1
        if (String(nextMonthNumber).length === 1) {
            nextMonthNumber = '0' + nextMonthNumber
        }
        window.location.href = `./EmployeeList.php?thisMonth=${nextMonthNumber}`;
    }
    if (thisMonth.innerText === '12月') {
        nextMonth.remove()
    }
}());

// 確認ボタンの操作
let verification = document.getElementsByClassName('root-detail-employee-row-verification')
for (let index in verification) {
    verification[index].onclick = function (event) {
        let index = /^.*-([0-9]*)$/.exec(event.target.id)[1];
        let employeeId = document.getElementById(`root-detail-employee-row-employeeId-${index}`).innerText

        let thisMonth = document.getElementById('root-menu-thisMonth')
        let thisMonthNumber = parseInt(/^([0-9]*)月$/.exec(thisMonth.innerText)[1])
        if (String(thisMonthNumber).length === 1) {
            thisMonthNumber = '0' + thisMonthNumber
        }

        window.location.href = `./AttendanceVerification.php?employeeId=${employeeId}&thisMonth=${thisMonthNumber}`;
    }
}