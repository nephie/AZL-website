'*******************************'
'		            	        '
'	Configuratie            	'
'			                    '
'*******************************'

strFiledir = "files"

strDattargetvista = "ProgramData\Fujifilm Medical Systems\Synapse\Workstation\config"
strDattargetxp = ""
strRegfile = "RegistrySettings.reg"
strPowerjacketRegfile = "Synapse_powerjacketfix.reg"
strHttppasswordRegfile = "http_password.reg"
strOcgetRegfile = "CodeBaseSearchPath.Verwijder.reg"
strActXReg = "activex_settings.reg"
strDatasourcedat = "SynapseDatasource.dat"
strSecuritydat = "SynapseSecurity.dat"
strMsi = "SynapseWorkstation.msi"
strObl = "Obliquus.msi"

'*******************************'
'			                	'
'	Einde Configuratie	        '
'			                	'
'*******************************'
'	Filesystem
Set objFSO = CreateObject ("Scripting.FileSystemObject")

'   Shell (environment en dergelijke)
set objWSH = CreateObject("WScript.Shell")
'objWSH.run strScriptserver & "\lib\startmessage.vbs"

'   Network
set objNet = createObject("WScript.Network")


'	Welk operating system? We differentiëren tussen Vista en andere windowsversies
Set objWMIService = GetObject("winmgmts:\\.\root\CIMV2")
Set colItems = objWMIService.ExecQuery("SELECT * FROM Win32_OperatingSystem")

For Each objItem In colItems
 strOsResult = objItem.Caption
 strSystemDrive = objItem.SystemDrive
Next

'	Vista of xp?
If InStr(strOsResult, "Vista") then
	strDattarget = strSystemDrive & "\" & strDattargetvista
Else
	strDattarget = strSystemDrive & "\" & strDattargetxp
End If

strFiledir = objFSO.GetParentFolderName(WScript.ScriptFullName) & "\" & strFiledir


'	Start installatie
'	Eerst de msi
Call objWSH.Run("msiexec.exe  /i """ & strFiledir & "\" & strMsi & """ CODEBASE=pacsdbsvr01 DISABLEICON=1 KILLEXPLORER=1", 5 , true)
Call objWSH.Run("msiexec.exe  /i """ & strFiledir & "\" & strObl & """ CODEBASE=pacsdbsvr01 DISABLEICON=1 KILLEXPLORER=1", 5 , true)

'	Dan de datfiles
Call objFSO.CopyFile(strFiledir & "\" & strDatasourcedat , strDattarget & "\" & strDatasourcedat)
Call objFSO.CopyFile(strFiledir & "\" & strSecuritydat , strDattarget & "\" & strSecuritydat)

'	En als laatste de registry files
Call objWSH.Run("regedit /s " & strFiledir & "\" & strRegfile)
Call objWSH.Run("regedit /s " & strFiledir & "\" & strPowerjacketRegfile)
Call objWSH.Run("regedit /s " & strFiledir & "\" & strHttppasswordRegfile)
Call objWSH.Run("regedit /s " & strFiledir & "\" & strOcgetRegfile)
Call objWSH.Run("regedit /s " & strFiledir & "\" & strActXReg)


'	einde
WScript.Echo("De installatie van Synapse is voltooid.")
