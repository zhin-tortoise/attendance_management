// 先月ボタンの操作
(function () {
    let lastMonth = document.getElementById('root-menu-lastMonth')
    let thisMonth = document.getElementById('root-menu-thisMonth')
    lastMonth.onclick = function () {
        let employeeId = document.getElementById('root-employeeId').innerText
        let lastMonthNumber = parseInt(/^([0-9]*)月$/.exec(thisMonth.innerText)[1]) - 1
        if (String(lastMonthNumber).length === 1) {
            lastMonthNumber = '0' + lastMonthNumber
        }
        window.location.href = `./AttendanceVerification.php?employeeId=${employeeId}&thisMonth=${lastMonthNumber}`;
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
        let employeeId = document.getElementById('root-employeeId').innerText
        let nextMonthNumber = parseInt(/^([0-9]*)月$/.exec(thisMonth.innerText)[1]) + 1
        if (String(nextMonthNumber).length === 1) {
            nextMonthNumber = '0' + nextMonthNumber
        }
        window.location.href = `./AttendanceVerification.php?employeeId=${employeeId}&thisMonth=${nextMonthNumber}`;
    }
    if (thisMonth.innerText === '12月') {
        nextMonth.remove()
    }
}());

// 一括承認ボタンの操作
(function () {
    let bulkApproval = document.getElementById('root-menu-bulkApproval')
    bulkApproval.onclick = function () {
        let id = document.getElementsByClassName('root-detail-attendance-row-id')
        let attendanceIds = []
        for (let index in id) {
            if (typeof id[index].innerText === 'undefined' || isUnapplied(id, index)) {
                continue
            }

            attendanceIds.push(id[index].innerText)
        }

        let form = new FormData()
        form.append('attendanceIds', JSON.stringify(attendanceIds))

        fetch('../Backend/Api/BulkApproveApi.php', {
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

// 一括取り下げのボタンの操作
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

// 承認ボタンの操作
(function () {
    let approval = document.getElementsByClassName('root-detail-attendance-row-approval')
    for (let index in approval) {
        approval[index].onclick = function (event) {
            let attendanceIndex = /^.*-([0-9]*)$/.exec(event.target.id)[1];
            let status = document.getElementById(`root-detail-attendance-row-status-${attendanceIndex}`)
            if (status.innerText === '未申請') {
                alert('未申請のため、承認できません。')
                return
            }

            let attendanceId = document.getElementById(`root-detail-attendance-row-id-${attendanceIndex}`).innerText
            let form = new FormData()
            form.append('attendanceId', attendanceId)

            fetch('../Backend/Api/approveApi.php', {
                    method: 'POST',
                    body: form
                })
                .then(response => response.json())
                .then(data => {
                    errorCode = data.errorCode
                    if (parseInt(errorCode) !== 0) {
                        alert('承認に失敗しました。')
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
                alert('承認済みのため、取り下げできません。')
                return
            }

            let attendanceId = document.getElementById(`root-detail-attendance-row-id-${attendanceIndex}`).innerText
            let form = new FormData()
            form.append('attendanceId', attendanceId)

            fetch('../Backend/Api/cancelApi.php', {
                    method: 'POST',
                    body: form
                })
                .then(response => response.json())
                .then(data => {
                    errorCode = data.errorCode
                    if (parseInt(errorCode) !== 0) {
                        alert('承認に失敗しました。')
                    }

                    window.location.href = getHref()
                })
        }
    }
}());

// 承認済みかどうか
function isApproved(id, index) {
    let attendanceIndex = /^.*-([0-9]*)$/.exec(id[index].id)[1];
    let status = document.getElementById(`root-detail-attendance-row-status-${attendanceIndex}`)

    return status.innerText === '承認済み'
}

// 未申請かどうか
function isUnapplied(id, index) {
    let attendanceIndex = /^.*-([0-9]*)$/.exec(id[index].id)[1];
    let status = document.getElementById(`root-detail-attendance-row-status-${attendanceIndex}`)

    return status.innerText === '未申請'
}

// リンクの取得
function getHref() {

    let employeeId = document.getElementById('root-employeeId').innerText
    let thisMonth = document.getElementById('root-menu-thisMonth')
    let thisMonthNumber = parseInt(/^([0-9]*)月$/.exec(thisMonth.innerText)[1])
    if (String(thisMonthNumber).length === 1) {
        thisMonthNumber = '0' + thisMonthNumber
    }

    return `./AttendanceVerification.php?employeeId=${employeeId}&thisMonth=${thisMonthNumber}`;
}