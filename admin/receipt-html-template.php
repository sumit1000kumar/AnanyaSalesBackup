<?php
function checkboxMark($value, $match) {
    return ($value ?? '') === $match ? '&#10003;' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ananya Sales & Service Report</title>
    <style>
        <?php include 'style.css'; ?>
        .checkbox:after {
            content: "\2713";
            display: inline-block;
            font-size: 10px;
            color: #000;
            visibility: hidden;
        }
        .checked .checkbox:after {
            visibility: visible;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header">
            <div class="company-logo">
                <div class="logo-triangle"></div>
                <span class="company-name">Ananya Sales & Service</span>
            </div>
            <div class="company-info">
                702, The Gold Crest, Plot no.5, Phase 2,<br>
                Navde Colony, Navde, Panvel - 410206<br>
                Maharashtra<br>
                Mob. : 9699555898 | 8104223378<br>
                Email : devaldar@gmail.com<br>
                ananyasalesservice@gmail.com
            </div>
            <div class="service-header">
                SERVICE REPORT : SER/ASS<br>
                <div class="service-number">No.: 1700</div>
            </div>
        </div>

        <div class="form-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Customer Name :</label>
                    <div class="form-input"><?= htmlspecialchars($customer_name) ?></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Attended By :</label>
                    <div class="form-input"><?= htmlspecialchars($engineer) ?></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Address :</label>
                    <div class="form-input"><?= htmlspecialchars($address) ?></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Date :</label>
                    <div class="form-input"><?= htmlspecialchars($service_date) ?></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone No. :</label>
                    <div class="form-input"><?= htmlspecialchars($phone) ?></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email ID :</label>
                    <div class="form-input"><?= htmlspecialchars($email) ?></div>
                </div>
            </div>

            <div class="nature-visit">
                <strong>Nature of Visit:</strong> <?= htmlspecialchars($nature_of_visit) ?>
            </div>

            <div class="equipment-section">
                <div class="equipment-header">
                    <div class="equipment-col">Equipment:</div>
                    <div class="equipment-col">Model No.:</div>
                    <div class="equipment-col">Serial No.:</div>
                </div>
                <div class="equipment-row">
                    <div class="equipment-cell"><?= htmlspecialchars($equipment) ?></div>
                    <div class="equipment-cell"><?= htmlspecialchars($model_no) ?></div>
                    <div class="equipment-cell"><?= htmlspecialchars($serial_no) ?></div>
                </div>
            </div>

            <div class="checklist-section">
                <div class="checklist-left">
                    <div class="checklist-title">AMC Checklist</div>
                    <?php
                    $items = [
                        'Compressor Current', 'Condenser Fan Motor', 'All Electric Connections',
                        'Instrument Temp', 'Blower Motor', 'Heater', 'Carboun Brush',
                        'Centrifuge Rpm', 'Recorder', 'Agitator Motor', 'Cleaning',
                        'AMC Visit 1D203C4D', 'Warranty & Paid Service'
                    ];
                    foreach ($items as $item):
                        $val = $checklist[$item] ?? '';
                    ?>
                    <div class="checklist-item">
                        <div class="checklist-bullet"></div>
                        <div class="checklist-text"><?= $item ?></div>
                        <div class="checkbox-group">
                            <div class="checkbox-item <?= $val === 'Yes' ? 'checked' : '' ?>">
                                <div class="checkbox"></div><span>Yes</span>
                            </div>
                            <div class="checkbox-item <?= $val === 'No' ? 'checked' : '' ?>">
                                <div class="checkbox"></div><span>No</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="checklist-right">
                    <div class="checklist-title">Service Rendered:</div>
                    <div class="form-input"><?= nl2br(htmlspecialchars($service_rendered)) ?></div>
                </div>
            </div>

            <div class="section-title">Our Remarks:</div>
            <div class="remarks-section">
                <div class="remarks-lines">
                    <?php foreach (explode("\n", $our_remarks) as $line): ?>
                        <div class="large-input"><?= htmlspecialchars($line) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="section-title">Spares Parts Used :</div>
            <?php foreach (explode("\n", $spare_parts) as $part): ?>
                <div class="large-input"><?= htmlspecialchars($part) ?></div>
            <?php endforeach; ?>

            <div class="section-title">Recommended:</div>
            <?php foreach (explode("\n", $recommended) as $rec): ?>
                <div class="large-input"><?= htmlspecialchars($rec) ?></div>
            <?php endforeach; ?>

            <div class="section-title">Customer's Remarks:</div>
            <?php foreach (explode("\n", $customer_remarks) as $cmt): ?>
                <div class="large-input"><?= htmlspecialchars($cmt) ?></div>
            <?php endforeach; ?>

            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-col">
                        <label class="form-label">Customer's Name :</label>
                        <div class="signature-line"></div>
                    </div>
                    <div class="signature-col">
                        <label class="form-label">Signature</label>
                        <div class="signature-line"></div>
                    </div>
                    <div class="signature-col">
                        <label class="form-label">Engineer Signature</label>
                        <div class="signature-line"></div>
                    </div>
                </div>
                <div class="signature-row">
                    <div class="signature-col">
                        <label class="form-label">Designation</label>
                        <div class="signature-line"><?= htmlspecialchars($designation) ?></div>
                    </div>
                    <div class="signature-col">
                        <label class="form-label">Mobile No.</label>
                        <div class="signature-line"><?= htmlspecialchars($phone) ?></div>
                    </div>
                    <div class="signature-col"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
