// 始業時刻の操作
// 終業時刻の操作
// 休憩時間の操作
(function () {
    let startTimeInput = document.getElementById('root-startTimeInput')
    let finishTimeInput = document.getElementById('root-finishTimeInput')
    let breakTimeInput = document.getElementById('root-breakTimeInput')

    startTimeInput.onchange = function () {
        changeWorkingHours(startTimeInput.value, finishTimeInput.value, breakTimeInput.value)
        changeOvertime(startTimeInput.value, finishTimeInput.value)
        changeMidnightOvertime(finishTimeInput.value)
        changeAbsenceHours(startTimeInput.value, finishTimeInput.value)
    }

    finishTimeInput.onchange = function () {
        changeWorkingHours(startTimeInput.value, finishTimeInput.value, breakTimeInput.value)
        changeOvertime(startTimeInput.value, finishTimeInput.value)
        changeMidnightOvertime(finishTimeInput.value)
        changeAbsenceHours(startTimeInput.value, finishTimeInput.value)
    }

    breakTimeInput.onchange = function () {
        changeWorkingHours(startTimeInput.value, finishTimeInput.value, breakTimeInput.value)
        changeOvertime(startTimeInput.value, finishTimeInput.value)
        changeMidnightOvertime(finishTimeInput.value)
        changeAbsenceHours(startTimeInput.value, finishTimeInput.value)
    }
}())

// 勤務時間の操作
function changeWorkingHours(startTime, finishTime, breakTime) {
    let workingHours = document.getElementById('root-workingHours')
    workingHours.innerText = '勤務時間 : ' + subTime(subTime(finishTime, startTime), breakTime)
}

// 残業時間の操作
function changeOvertime(startTime, finishTime) {
    let overtime = document.getElementById('root-overtime')
    overtime.innerText = '残業時間 : ' + addTime(subTime('9:00', startTime), subTime(finishTime, '18:00'))
}

// 深夜時間の操作
function changeMidnightOvertime(finishTime) {
    let midnightOvertime = document.getElementById('root-midnightOvertime')
    midnightOvertime.innerText = '深夜時間 : ' + subTime(finishTime, '22:00')
}

// 欠課時間の操作
function changeAbsenceHours(startTime, finishTime) {
    let absenceHours = document.getElementById('root-absenceHours')
    absenceHours.innerText = '欠課時間 : ' + addTime(subTime(startTime, '9:00'), subTime('18:00', finishTime))
}

// タイムの加算
function addTime(fromTime, time) {
    let fromHours = /^([0-9]*):/.exec(fromTime)[1]
    let hours = /^([0-9]*):/.exec(time)[1]
    let fromMinutes = /:([0-9]*)$/.exec(fromTime)[1]
    let minutes = /:([0-9]*)$/.exec(time)[1]

    let addHours = parseInt(fromHours) + parseInt(hours)
    let addMinutes = parseInt(fromMinutes) + parseInt(minutes)

    if (addMinutes >= 60) {
        addHours += 1
        addMinutes -= 60
    }

    addHours = fillTime(addHours)
    addMinutes = fillTime(addMinutes)

    return addHours + ':' + addMinutes
}

// タイムの減算
function subTime(fromTime, time) {
    let fromHours = /^([0-9]*):/.exec(fromTime)[1]
    let hours = /^([0-9]*):/.exec(time)[1]
    let fromMinutes = /:([0-9]*)$/.exec(fromTime)[1]
    let minutes = /:([0-9]*)$/.exec(time)[1]

    let subHours = parseInt(fromHours) - parseInt(hours)
    let subMinutes = parseInt(fromMinutes) - parseInt(minutes)

    if (subMinutes < 0) {
        subHours -= 1
        subMinutes += 60
    }

    subHours = fillTime(subHours)
    subMinutes = fillTime(subMinutes)

    if (subHours < 0) {
        subHours = '00'
        subMinutes = '00'
    }

    return subHours + ':' + subMinutes
}

// 1桁の場合、0埋め
function fillTime(number) {
    if (String(number).length === 1) {
        number = '0' + number
    }

    return number
}

// 更新ボタンの操作
(function () {
    let button = document.getElementById('root-button')
    button.onclick = function () {
        if (document.getElementById('root-status').innerText === '承認済み') {
            alert('承認済みの勤怠は更新できません。')
            return
        }

        let date = document.getElementById('root-date')
        let thisMonth = getThisMonth(date)
        let workingDay = getWorkingDay(date, thisMonth)
        let overtime = /([0-9]*:[0-9]*)$/.exec(document.getElementById('root-overtime').innerText)[1]
        let midnightOvertime = /([0-9]*:[0-9]*)$/.exec(document.getElementById('root-midnightOvertime').innerText)[1]
        let normalOvertime = subTime(overtime, midnightOvertime)

        let form = new FormData
        form.append('attendanceId', document.getElementById('root-attendanceId').innerText)
        form.append('employeeId', document.getElementById('root-employeeId').innerText)
        form.append('workingDay', workingDay)
        form.append('dayOfTheWeek', /([月火水木金土日])曜日$/.exec(date.innerText)[1])
        form.append('status', document.getElementById('root-status').innerText)
        form.append('startTime', document.getElementById('root-startTimeInput').value)
        form.append('finishTime', document.getElementById('root-finishTimeInput').value)
        form.append('breakTime', document.getElementById('root-breakTimeInput').value)
        form.append('workingHours', /([0-9]*:[0-9]*)$/.exec(document.getElementById('root-workingHours').innerText)[1])
        form.append('overtime', overtime)
        form.append('normalOvertime', normalOvertime)
        form.append('midnightOvertime', midnightOvertime)
        form.append('absenceHours', /([0-9]*:[0-9]*)$/.exec(document.getElementById('root-absenceHours').innerText)[1])
        form.append('remarks', document.getElementById('root-remarksInput').value)

        fetch('../Backend/Api/UpdateAttendanceApi.php', {
                method: 'POST',
                body: form
            })
            .then(response => response.json())
            .then(data => {
                errorCode = data.errorCode
                if (parseInt(errorCode) !== 0) {
                    alert('更新に失敗しました。')
                }

                window.location.href = `./AttendanceList.php?thisMonth=${thisMonth}`
            })
    }

    // 今月の取得
    function getThisMonth(date) {
        let thisMonth = /^([0-9]*)月/.exec(date.innerText)[1]
        if (String(thisMonth).length === 1) {
            thisMonth = '0' + thisMonth
        }

        return thisMonth
    }

    // 勤務日の取得
    function getWorkingDay(date, thisMonth) {
        let currentDate = new Date()
        let workingDay = /月([0-9]*)日/.exec(date.innerText)[1]
        if (String(workingDay).length === 1) {
            workingDay = '0' + workingDay
        }

        return `${currentDate.getFullYear()}-${thisMonth}-${workingDay}`
    }
}())