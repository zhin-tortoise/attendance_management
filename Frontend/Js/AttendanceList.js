// ログアウトボタンの動作
(function () {
    let logout = document.getElementById('root-navigation-logout')
    logout.onclick = function () {
        fetch('../Backend/Api/EmployeeLogoutApi.php')
            .then(response => response.json())

        window.location.href = './EmployeeLogin.html';
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
        window.location.href = `./AttendanceList.php?thisMonth=${lastMonthNumber}`;
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
        window.location.href = `./AttendanceList.php?thisMonth=${nextMonthNumber}`;
    }
    if (thisMonth.innerText === '12月') {
        nextMonth.remove()
    }
}());

// 一括申請ボタンの操作
(function () {
    let bulkRequest = document.getElementById('root-menu-bulkRequest')
    bulkRequest.onclick = function () {
        let id = document.getElementsByClassName('root-detail-attendance-row-id')
        let attendanceIds = []
        for (let index in id) {
            if (typeof id[index].innerText === 'undefined' || isApproved(id, index)) {
                continue
            }

            attendanceIds.push(id[index].innerText)
        }

        let form = new FormData()
        form.append('attendanceIds', JSON.stringify(attendanceIds))

        fetch('../Backend/Api/BulkRequestApi.php', {
                method: 'POST',
                body: form
            })
            .then(response => response.json())
            .then(data => {
                errorCode = data.errorCode
                if (parseInt(errorCode) !== 0) {
                    alert('申請に失敗しました。')
                }

                window.location.href = getHref()
            })
    }
}());

// 一括取り下げの操作
(function () {
    let bulkCancel = document.getElementById('root-menu-bulkCancel')
    bulkCancel.onclick = function () {
        let id = document.getElementsByClassName('root-detail-attendance-row-id')
        let attendanceIds = []
        for (let index in id) {
            if (typeof id[index].innerText === 'undefined' || isApproved(id, index)) {
                continue
            }

            attendanceIds.push(id[index].innerText)
        }

        let form = new FormData()
        form.append('attendanceIds', JSON.stringify(attendanceIds))

        fetch('../Backend/Api/BulkCancelApi.php', {
                method: 'POST',
                body: form
            })
            .then(response => response.json())
            .then(data => {
                errorCode = data.errorCode
                if (parseInt(errorCode) !== 0) {
                    alert('申請に失敗しました。')
                }

                window.location.href = getHref()
            })
    }
}());

// 申請ボタンの操作
(function () {
    let request = document.getElementsByClassName('root-detail-attendance-row-request')
    for (let index in request) {
        request[index].onclick = function (event) {
            let attendanceIndex = /^.*-([0-9]*)$/.exec(event.target.id)[1];
            let status = document.getElementById(`root-detail-attendance-row-status-${attendanceIndex}`)
            if (status.innerText === '承認済み') {
                alert('承認済みのため、申請できません。')
                return
            }

            let attendanceId = document.getElementById(`root-detail-attendance-row-id-${attendanceIndex}`).innerText
            let form = new FormData()
            form.append('attendanceId', attendanceId)

            fetch('../Backend/Api/RequestApi.php', {
                    method: 'POST',
                    body: form
                })
                .then(response => response.json())
                .then(data => {
                    errorCode = data.errorCode
                    if (parseInt(errorCode) !== 0) {
                        alert('申請に失敗しました。')
                    }

                    window.location.href = getHref()
                })
        }
    }
}());

// 取り下げボタンの操作
(function () {
    let cancel = document.getElementsByClassName('root-detail-attendance-row-cancel')
    for (let index in cancel) {
        cancel[index].onclick = function (event) {
            let attendanceIndex = /^.*-([0-9]*)$/.exec(event.target.id)[1];
            let status = document.getElementById(`root-detail-attendance-row-status-${attendanceIndex}`)
            if (status.innerText === '承認済み') {
                alert('承認済みの勤怠は取り下げできません。')
                return
            }

            let attendanceId = document.getElementById(`root-detail-attendance-row-id-${attendanceIndex}`).innerText
            let form = new FormData()
            form.append('attendanceId', attendanceId)

            fetch('../Backend/Api/CancelApi.php', {
                    method: 'POST',
                    body: form
                })
                .then(response => response.json())
                .then(data => {
                    errorCode = data.errorCode
                    if (parseInt(errorCode) !== 0) {
                        alert('申請に失敗しました。')
                    }

                    window.location.href = getHref()
                })
        }
    }
}());

// 編集ボタンの動作
(function () {
    let edit = document.getElementsByClassName('root-detail-attendance-row-edit')
    for (let index in edit) {
        edit[index].onclick = function (event) {
            let attendanceIndex = /^.*-([0-9]*)$/.exec(event.target.id)[1];
            let attendanceId = document.getElementById(`root-detail-attendance-row-id-${attendanceIndex}`)
            let status = document.getElementById(`root-detail-attendance-row-status-${attendanceIndex}`)
            let thisMonth = document.getElementById('root-menu-thisMonth')
            let workingDay = document.getElementById(`root-detail-attendance-row-workingDay-${attendanceIndex}`)
            let dayOfTheWeek = document.getElementById(`root-detail-attendance-row-dayOfTheWeek-${attendanceIndex}`)
            let startTime = document.getElementById(`root-detail-attendance-row-stareTime-${attendanceIndex}`)
            let finishTime = document.getElementById(`root-detail-attendance-row-finishTime-${attendanceIndex}`)
            let breakTime = document.getElementById(`root-detail-attendance-row-brakeTime-${attendanceIndex}`)
            let remarks = document.getElementById(`root-detail-attendance-row-remarks-${attendanceIndex}`)
            let workingHours = document.getElementById(`root-detail-attendance-row-workingHours-${attendanceIndex}`)
            let overtime = document.getElementById(`root-detail-attendance-row-overtime-${attendanceIndex}`)
            let midnightOvertime = document.getElementById(`root-detail-attendance-row-midnightOvertime-${attendanceIndex}`)
            let absenceHours = document.getElementById(`root-detail-attendance-row-absenceHours-${attendanceIndex}`)

            window.location.href = './AttendanceEdit.php' +
                `?attendanceId=${attendanceId.innerText}` +
                `&status=${status.innerText}` +
                `&thisMonth=${parseInt(thisMonth.innerText)}` +
                `&workingDay=${workingDay.innerText}` +
                `&dayOfTheWeek=${dayOfTheWeek.innerText}` +
                `&startTime=${startTime.innerText}` +
                `&finishTime=${finishTime.innerText}` +
                `&breakTime=${breakTime.innerText}` +
                `&remarks=${remarks.innerText}` +
                `&workingHours=${workingHours.innerText}` +
                `&overtime=${overtime.innerText}` +
                `&midnightOvertime=${midnightOvertime.innerText}` +
                `&absenceHours=${absenceHours.innerText}`
        }
    }
}());

// 承認済みかどうか
function isApproved(id, index) {
    let attendanceIndex = /^.*-([0-9]*)$/.exec(id[index].id)[1];
    let status = document.getElementById(`root-detail-attendance-row-status-${attendanceIndex}`)

    return status.innerText === '承認済み'
}

// リンクの取得
function getHref() {
    let thisMonth = document.getElementById('root-menu-thisMonth')
    let thisMonthNumber = parseInt(/^([0-9]*)月$/.exec(thisMonth.innerText)[1])
    if (String(thisMonthNumber).length === 1) {
        thisMonthNumber = '0' + thisMonthNumber
    }

    return `./AttendanceList.php?thisMonth=${thisMonthNumber}`;
}