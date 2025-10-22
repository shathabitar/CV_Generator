<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CV Generator')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>


<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

<script>
    $(document).ready(function() {
        $('select').select2({
            placeholder: "Select skills",
            width: '100%'
        });
    });

    let eduIndex = 1;

    function addEducation() {
        const wrapper = document.getElementById('education-wrapper');
        const newEntry = document.querySelector('.education-entry').cloneNode(true);

        // Clear values
        newEntry.querySelectorAll('input').forEach(input => input.value = '');

        // Update names with new index
        newEntry.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/\d+/, eduIndex));
        });

        wrapper.appendChild(newEntry);
        eduIndex++;
    }
</script>
