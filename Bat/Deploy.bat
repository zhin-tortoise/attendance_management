set API_PATH=D:\Apache24\htdocs\AttendanceManagement\Backend\Api
if not exist %API_PATH% mkdir %API_PATH%
copy .\Backend\Api\* %API_PATH%

set APPLICATION_PATH=D:\Apache24\htdocs\AttendanceManagement\Backend\Application
if not exist %APPLICATION_PATH% mkdir %APPLICATION_PATH%
copy .\Backend\Application\* %APPLICATION_PATH%

set DOMAIN_PATH=D:\Apache24\htdocs\AttendanceManagement\Backend\Domain
if not exist %DOMAIN_PATH% mkdir %DOMAIN_PATH%
copy .\Backend\Domain\* %DOMAIN_PATH%

set REPOSITORY_PATH=D:\Apache24\htdocs\AttendanceManagement\Backend\Repository
if not exist %REPOSITORY_PATH% mkdir %REPOSITORY_PATH%
copy .\Backend\Repository\* %REPOSITORY_PATH%

set CSS_PATH=D:\Apache24\htdocs\AttendanceManagement\Frontend\Css
if not exist %CSS_PATH% mkdir %CSS_PATH%
copy .\Frontend\Css\* %CSS_PATH%

set JS_PATH=D:\Apache24\htdocs\AttendanceManagement\Frontend\Js
if not exist %JS_PATH% mkdir %JS_PATH%
copy .\Frontend\Js\* %JS_PATH%

set PICTURE_PATH=D:\Apache24\htdocs\AttendanceManagement\Frontend\Picture
if not exist %PICTURE_PATH% mkdir %PICTURE_PATH%
copy .\Frontend\Picture\* %PICTURE_PATH%

set HTML_PATH=D:\Apache24\htdocs\AttendanceManagement\Frontend\
if not exist %HTML_PATH% mkdir %HTML_PATH%
copy .\Frontend\* %HTML_PATH%