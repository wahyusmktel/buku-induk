Option Explicit
Dim WshShell
Set WshShell = CreateObject("WScript.Shell")

' Kill PHP server on port 5779
WshShell.Run "cmd /c for /f ""tokens=5"" %a in ('netstat -aon 2^>nul ^| findstr "":5779 ""') do taskkill /f /pid %a >nul 2>&1", 0, True

MsgBox "Buku Induk telah dihentikan.", vbInformation, "Buku Induk"
