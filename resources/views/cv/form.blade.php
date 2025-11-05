@extends('layout')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Create Your CV</h2>

    <form action="{{ route('cv.generate') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Profile Picture -->
        <div class="mb-3">
            <label class="form-label fw-bold">Profile Picture:</label>
            <input type="file" class="form-control" name="photo">
        </div>

        <!-- Full Name -->
        <div class="mb-3">
            <label class="form-label fw-bold">Full Name:</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <!-- About -->
        <div class="mb-3">
            <label class="form-label fw-bold">About You:</label>
            <textarea name="about" class="form-control" rows="4" required></textarea>
        </div>

        <!-- Education -->
        <div class="mb-3">
            <label class="form-label fw-bold">Education:</label>
            <div id="education-wrapper">
                <div class="education-entry mb-3 border p-3 rounded shadow-sm">
                    <input type="text" name="education[0][degree]" class="form-control mb-2" placeholder="Degree" required>
                    <input type="text" name="education[0][institution]" class="form-control mb-2" placeholder="Institution" required>
                    <input type="text" name="education[0][year]" class="form-control mb-2" placeholder="Year" required>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary mb-3" onclick="addEducation()">+ Add Education</button>
        </div>

        <!-- Experience -->
        <div class="mb-3">
            <label class="form-label fw-bold">Experience:</label>
            <div id="experience-wrapper">
                <div class="experience-entry mb-3 border p-3 rounded shadow-sm">
                    <input type="text" name="experience[0][position]" class="form-control mb-2" placeholder="Position" required>
                    <input type="text" name="experience[0][company]" class="form-control mb-2" placeholder="Company/Organization" required>
                    <input type="text" name="experience[0][start_date]" class="form-control mb-2" placeholder="Start Date" required>
                    <input type="text" name="experience[0][end_date]" class="form-control mb-2" placeholder="End Date">
                    <textarea name="experience[0][description]" class="form-control" placeholder="Description"></textarea>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary mb-3" onclick="addExperience()">+ Add Experience</button>
        </div>

        <!-- Certificates -->

        <div class="mb-3">
            <label class="form-label fw-bold">Certificates:</label>
            <div id="certificate-wrapper">
                <div class="certificate-entry mb-3 border p-3 rounded shadow-sm">
                    <input type="text" name="certificate[0][title]" class="form-control mb-2" placeholder="Title" required>
                    <input type="text" name="certificate[0][company]" class="form-control mb-2" placeholder="Company/Organization" required>
                    <input type="text" name="certificate[0][date]" class="form-control mb-2" placeholder="Date" required>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary mb-3" onclick="addCertificate()">+ Add Certificate</button>
        </div>

        <!-- Technical Skills -->
        <div class="mb-3">
            <label class="form-label fw-bold">Technical Skills:</label>
            <select name="technical_skills[]" class="form-select mb-2" multiple>
                @foreach($technicalSkills as $skill)
                    <option value="{{ $skill->skill_name }}">{{ $skill->skill_name }}</option>
                @endforeach
            </select>
            <input type="text" class="form-control mt-1" name="custom_technical_skills[]" placeholder="Add custom technical skill">
            <small class="text-muted">Hold Ctrl (or Cmd on Mac) to select multiple from the list.</small>
        </div>

        <!-- Soft Skills -->
        <div class="mb-3">
            <label class="form-label fw-bold">Soft Skills:</label>
            <select name="soft_skills[]" class="form-select mb-2" multiple>
                @foreach($softSkills as $skill)
                    <option value="{{ $skill->skill_name }}">{{ $skill->skill_name }}</option>
                @endforeach
            </select>
            <input type="text" class="form-control mt-1" name="custom_soft_skills[]" placeholder="Add custom soft skill">
            <small class="text-muted">Hold Ctrl (or Cmd on Mac) to select multiple from the list.</small>
        </div>

        <!-- References -->
        <div class="mb-3">
            <label class="form-label fw-bold">References:</label>
            <div id="reference-wrapper">
                <div class="reference-entry mb-3 border p-3 rounded shadow-sm">
                    <input type="text" name="reference[0][name]" class="form-control mb-2" placeholder="Name" required>
                    <input type="text" name="reference[0][company]" class="form-control mb-2" placeholder="Company/Organization" required>
                    <input type="text" name="reference[0][phone_number]" class="form-control mb-2" placeholder="Phone Number" required>
                    <input type="email" name="reference[0][email]" class="form-control mb-2" placeholder="Email" required>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary mb-3" onclick="addReference()">+ Add Reference</button>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Preview CV</button>
    </form>
</div>

<script>
let eduIndex = 1;
let expIndex = 1;
let refIndex = 1;
let certIndex = 1;

function addEducation() {
    const wrapper = document.getElementById('education-wrapper');
    const newEntry = document.querySelector('.education-entry').cloneNode(true);
    newEntry.querySelectorAll('input').forEach(input => input.value = '');
    newEntry.querySelectorAll('input').forEach(input => {
        input.name = input.name.replace(/\d+/, eduIndex);
    });
    wrapper.appendChild(newEntry);
    eduIndex++;
}

function addExperience() {
    const wrapper = document.getElementById('experience-wrapper');
    const newEntry = document.querySelector('.experience-entry').cloneNode(true);
    newEntry.querySelectorAll('input, textarea').forEach(input => input.value = '');
    newEntry.querySelectorAll('input, textarea').forEach(input => {
        input.name = input.name.replace(/\d+/, expIndex);
    });
    wrapper.appendChild(newEntry);
    expIndex++;
}

function addCertificate() {
    const wrapper = document.getElementById('certificate-wrapper');
    const newEntry = document.querySelector('.certificate-entry').cloneNode(true);
    newEntry.querySelectorAll('input, textarea').forEach(input => input.value = '');
    newEntry.querySelectorAll('input, textarea').forEach(input => {
        input.name = input.name.replace(/\d+/, certIndex);
    });
    wrapper.appendChild(newEntry);
    certIndex++;
}

function addReference() {
    const wrapper = document.getElementById('reference-wrapper');
    const newEntry = document.querySelector('.reference-entry').cloneNode(true);
    newEntry.querySelectorAll('input').forEach(input => input.value = '');
    newEntry.querySelectorAll('input').forEach(input => {
        input.name = input.name.replace(/\d+/, refIndex);
    });
    wrapper.appendChild(newEntry);
    refIndex++;
}
</script>

@endsection
