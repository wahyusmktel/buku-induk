Option Explicit
Dim WshShell, AppDir, StartBat, fso

Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

AppDir = fso.GetParentFolderName(WScript.ScriptFullName)
StartBat = AppDir & "\start.bat"

' Kill any PHP server already on port 5779
WshShell.Run "cmd /c for /f ""tokens=5"" %a in ('netstat -aon 2^>nul ^| findstr "":5779 ""') do taskkill /f /pid %a >nul 2>&1", 0, True

' Start server silently
WshShell.Run """" & StartBat & """", 0, False

' Wait for server to start
WScript.Sleep 3000

' Open browser
WshShell.Run "http://localhost:5779"
