; ============================================
; Buku Induk - Installer Script
; Dibuat dengan Inno Setup 6
; ============================================

#define MyAppName "Buku Induk"
#define MyAppVersion "1.0.0"
#define MyAppPublisher "SMK Telkom Lampung"
#define MyAppURL "http://localhost:5779"

[Setup]
AppId={{A3F8C2D1-4B7E-4F91-82AC-D6E3591A2C08}}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}
AppPublisherURL={#MyAppURL}
AppSupportURL={#MyAppURL}
DefaultDirName=C:\BukuInduk
DefaultGroupName={#MyAppName}
AllowNoIcons=yes
OutputDir=dist
OutputBaseFilename=BukuInduk-Setup
Compression=lzma2/ultra64
SolidCompression=yes
WizardStyle=modern
PrivilegesRequired=lowest
DisableProgramGroupPage=no

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"

[Tasks]
Name: "desktopicon"; Description: "Buat shortcut di Desktop"; GroupDescription: "Shortcut:"
Name: "autostart"; Description: "Jalankan Buku Induk otomatis saat Windows start"; GroupDescription: "Startup:"; Flags: unchecked

[Files]
; PHP 8.3 portable (download dari windows.php.net/download - NTS x64)
Source: "php\*"; DestDir: "{app}\php"; Flags: ignoreversion recursesubdirs createallsubdirs

; Laravel application
Source: "..\app\*"; DestDir: "{app}\www\app"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\bootstrap\app.php"; DestDir: "{app}\www\bootstrap"; Flags: ignoreversion
Source: "..\bootstrap\providers.php"; DestDir: "{app}\www\bootstrap"; Flags: ignoreversion
Source: "..\config\*"; DestDir: "{app}\www\config"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\database\migrations\*"; DestDir: "{app}\www\database\migrations"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\database\seeders\*"; DestDir: "{app}\www\database\seeders"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\database\factories\*"; DestDir: "{app}\www\database\factories"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\public\*"; DestDir: "{app}\www\public"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\resources\*"; DestDir: "{app}\www\resources"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\routes\*"; DestDir: "{app}\www\routes"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\storage\*"; DestDir: "{app}\www\storage"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "*.log"
Source: "..\vendor\*"; DestDir: "{app}\www\vendor"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "..\artisan"; DestDir: "{app}\www"; Flags: ignoreversion
Source: "..\composer.json"; DestDir: "{app}\www"; Flags: ignoreversion

; Scripts
Source: "scripts\first-run.bat"; DestDir: "{app}"; Flags: ignoreversion
Source: "scripts\start.bat"; DestDir: "{app}"; Flags: ignoreversion
Source: "scripts\launcher.vbs"; DestDir: "{app}"; Flags: ignoreversion
Source: "scripts\stop.vbs"; DestDir: "{app}"; Flags: ignoreversion

[Dirs]
Name: "{app}\www\storage\app\public"
Name: "{app}\www\storage\framework\sessions"
Name: "{app}\www\storage\framework\views"
Name: "{app}\www\storage\framework\cache\data"
Name: "{app}\www\storage\logs"
Name: "{app}\www\database"
Name: "{app}\www\bootstrap\cache"

[Icons]
Name: "{group}\Buka Buku Induk"; Filename: "{sys}\wscript.exe"; Parameters: """{app}\launcher.vbs"""; Comment: "Buka aplikasi Buku Induk di browser"
Name: "{group}\Stop Buku Induk"; Filename: "{sys}\wscript.exe"; Parameters: """{app}\stop.vbs"""; Comment: "Hentikan server Buku Induk"
Name: "{group}\{cm:UninstallProgram,{#MyAppName}}"; Filename: "{uninstallexe}"
Name: "{commondesktop}\Buku Induk"; Filename: "{sys}\wscript.exe"; Parameters: """{app}\launcher.vbs"""; Comment: "Buka aplikasi Buku Induk"; Tasks: desktopicon

[Run]
Filename: "{app}\first-run.bat"; StatusMsg: "Menyiapkan database dan konfigurasi..."; Flags: runhidden waituntilterminated
Filename: "{sys}\wscript.exe"; Parameters: """{app}\launcher.vbs"""; Description: "Buka Buku Induk sekarang"; Flags: nowait postinstall skipifsilent

[UninstallRun]
Filename: "cmd.exe"; Parameters: "/c for /f ""tokens=5"" %a in ('netstat -aon 2>nul ^| findstr "":5779 ""') do taskkill /f /pid %a"; Flags: runhidden waituntilterminated

[Registry]
Root: HKCU; Subkey: "Software\Microsoft\Windows\CurrentVersion\Run"; ValueType: string; ValueName: "{#MyAppName}"; ValueData: """{sys}\wscript.exe"" ""{app}\launcher.vbs"""; Tasks: autostart; Flags: uninsdeletevalue
