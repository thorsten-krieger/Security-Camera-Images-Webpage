@echo off

for /f %%i in ("%0") do set curpath=%%~dpi
rem echo %curpath%
cd %curpath%

echo.
echo ##################################################
echo # CSS kopieren und packen
echo ##################################################
cd css\
..\gzip -f -k *.css
cd  ..\


echo.
echo ##################################################
echo # JS kopieren und packen
echo ##################################################
cd js\
..\gzip -f -k *.js
rem ..\gzip -f -k *.css

	cd fancybox\source\
	..\..\..\gzip -f -k *.js
	..\..\..\gzip -f -k *.css

		cd helpers\
		..\..\..\..\gzip -f -k *.js
		..\..\..\..\gzip -f -k *.css
		cd  ..\

	cd  ..\..\

cd  ..\

