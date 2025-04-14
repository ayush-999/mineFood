<?php
include_once('header.php');

// Get basic system information (works on both Windows & Linux)
$osType = php_uname('s'); // Operating system name (e.g., "Windows NT")
$hostname = php_uname('n'); // Hostname
$kernelVersion = php_uname('r'); // Kernel/Windows version (e.g., "10.0")
$systemLanguage = getenv('LANG') ?: (getenv('LC_ALL') ?: 'Not detected');

// Windows-specific system info
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // 1. Get Windows Distro/Version Name
    $distroName = 'Windows';
    $wmi = new COM("WinMgmts:{impersonationLevel=impersonate}//./root/cimv2");
    $os = $wmi->ExecQuery("SELECT * FROM Win32_OperatingSystem");
    foreach ($os as $item) {
        $distroName = $item->Caption;
        break;
    }

    // 2. Get Last Boot Time (Windows)
    $lastBoot = shell_exec('systeminfo | find "System Boot Time"');
    $lastBoot = trim(str_replace('System Boot Time:', '', $lastBoot));

} else {
    // Fallback for Linux (if needed)
    $distroName = 'Linux (Not detected)';
    $lastBoot = exec('who -b | awk \'{print $3 " " $4}\'');

}
?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <b><?= htmlspecialchars($pageSubTitle ?? 'System Settings') ?></b>
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="accordion" id="systemInfoAccordion">
                        <div class="card shadow-none border">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-block text-left" type="button"
                                            data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                        System Information
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#systemInfoAccordion">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                        <tr>
                                            <th width="30%">OS Type</th>
                                            <td><?= htmlspecialchars($osType) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Canonical Hostname</th>
                                            <td><?= htmlspecialchars($hostname) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kernel/Windows Version</th>
                                            <td><?= htmlspecialchars($kernelVersion) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Distro Name</th>
                                            <td><?= htmlspecialchars($distroName) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Last Boot</th>
                                            <td><?= htmlspecialchars($lastBoot ?: 'Unknown') ?></td>
                                        </tr>
                                        <tr>
                                            <th>System Language</th>
                                            <td><?= htmlspecialchars($systemLanguage) ?></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once('footer.php') ?>