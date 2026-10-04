<style>
@page{margin:18mm 14mm}
*{box-sizing:border-box}
body{font-family:DejaVu Sans,Arial,sans-serif;color:#213449;font-size:11px;margin:0;background:#fff}
.header{padding:0 0 15px;margin:0 0 22px;border-bottom:3px solid #087e83;text-align:left}
.header:before{content:"{{ config('app.name') }}";display:block;color:#087e83;font-size:11px;font-weight:bold;text-transform:uppercase;letter-spacing:1.5px;margin:0 0 9px}
.header h1{font-size:22px;color:#15243b;letter-spacing:-.5px;margin:0 0 6px}
.header p{font-size:11px;color:#68778d;margin:3px 0}
h2,h3{color:#15243b;font-weight:bold;margin:20px 0 10px}
.summary{margin:14px 0 22px}
.summary-card{display:inline-block;vertical-align:top;min-width:160px;min-height:60px;margin:0 8px 8px 0;border:1px solid #dce7ed;border-radius:7px;padding:10px 13px;background:#f8fafc}
.summary-card h3{font-size:10px;color:#52677c;margin:0 0 6px}
.summary-card p{font-size:16px;font-weight:bold;color:#087e83;margin:0}
table,.table{width:100%;border-collapse:collapse;table-layout:auto;margin:12px 0 22px}
th,td{padding:8px 9px;border-bottom:1px solid #e7edf2;text-align:left;vertical-align:top;word-break:break-word}
th{background:#eaf4f3;color:#214e54;font-weight:bold;font-size:10px}
tr:nth-child(even) td{background:#f9fbfc}
tr{page-break-inside:avoid}
.text-right{text-align:right}
.footer{border-top:1px solid #dce7ed;margin-top:26px;padding-top:10px;color:#68778d;font-size:10px}
@media print{a{color:#15243b;text-decoration:none}button{display:none}}
</style>
