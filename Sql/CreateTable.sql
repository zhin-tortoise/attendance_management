create table employee (
    employee_id int auto_increment primary key,
    name varchar(255),
    mail_address varchar(255),
    password varchar(255)
);

create table attendance (
    attendance_id int auto_increment primary key,
    employee_id int,
    working_day date,
    day_of_the_week varchar(255),
    status varchar(255),
    start_time time,
    finish_time time,
    break_time time,
    working_hours time,
    overtime time,
    normal_overtime time,
    midnight_overtime time,
    absence_hours time,
    remarks text,
    foreign key (employee_id) references employee(employee_id) on delete cascade
);

create table team (
    authorizer_id int,
    employee_id int,
    primary key (authorizer_id, employee_id),
    foreign key (authorizer_id) references employee(employee_id) on delete cascade,
    foreign key (employee_id) references employee(employee_id) on delete cascade
);