# CV Generator - API Documentation

## 📋 Overview

The CV Generator provides both web interface and API endpoints for creating and managing CVs.

## 🔗 Base URL
```
http://localhost:8000
```

## 📝 Endpoints

### 1. Display CV Form
**GET** `/`
- **Description**: Shows the CV creation form
- **Response**: HTML form page
- **Route Name**: `cv.form`

### 2. Generate CV Preview
**POST** `/generate`
- **Description**: Processes form data and shows CV preview
- **Route Name**: `cv.generate`
- **Content-Type**: `multipart/form-data`

#### Request Parameters:
```json
{
  "name": "string (required, max:255)",
  "about": "string (required)",
  "photo": "file (optional, image, max:2MB)",
  "education": [
    {
      "degree": "string (required, max:255)",
      "institution": "string (required, max:255)", 
      "year": "string (required, max:50)"
    }
  ],
  "experience": [
    {
      "position": "string (required, max:255)",
      "company": "string (required, max:255)",
      "start_date": "string (required, max:50)",
      "end_date": "string (optional, max:50)",
      "description": "string (optional)"
    }
  ],
  "reference": [
    {
      "name": "string (required, max:255)",
      "company": "string (required, max:255)",
      "phone_number": "string (required, max:50)",
      "email": "email (required, max:255)"
    }
  ],
  "certificate": [
    {
      "title": "string (required, max:255)",
      "company": "string (required, max:255)",
      "date": "string (required, max:50)"
    }
  ],
  "technical_skills": ["string"],
  "soft_skills": ["string"]
}
```

#### Response:
- **Success**: HTML preview page
- **Error**: Validation errors with 422 status

### 3. Store CV Data
**POST** `/store`
- **Description**: Alternative endpoint for storing CV data
- **Route Name**: `cv.store`
- **Parameters**: Same as `/generate`

### 4. Download PDF (Form Data)
**POST** `/download-pdf`
- **Description**: Generates and downloads PDF from form data
- **Route Name**: `cv.download`
- **Content-Type**: `application/x-www-form-urlencoded`

#### Request Parameters:
All CV data fields as form data (same structure as generate endpoint)

#### Response:
- **Success**: PDF file download
- **Content-Type**: `application/pdf`
- **Filename**: `My_CV.pdf`

### 5. Preview Stored CV
**GET** `/cv/preview/{id}`
- **Description**: Shows preview of stored CV by user ID
- **Route Name**: `cv.preview`
- **Parameters**: 
  - `id` (integer, required): User ID

#### Response:
- **Success**: HTML preview page with download link
- **Error**: 404 if user not found

### 6. Download Stored CV PDF
**GET** `/cv/download-pdf/{id}`
- **Description**: Downloads PDF of stored CV
- **Route Name**: `cv.download.pdf`
- **Parameters**:
  - `id` (integer, required): User ID

#### Response:
- **Success**: PDF file download
- **Content-Type**: `application/pdf`
- **Filename**: `{user_name}_CV.pdf`

### 7. Generate CV Async
**POST** `/cv/generate-async/{id}`
- **Description**: Queues CV PDF generation and sends via email
- **Route Name**: `cv.generate.async`
- **Parameters**:
  - `id` (integer, required): User ID

#### Response:
```json
{
  "message": "Your CV is being generated. You will receive an email when it is ready."
}
```

## 🔒 Authentication

Currently, the application doesn't require authentication for basic CV generation. All endpoints are publicly accessible.

## 📊 Response Formats

### Success Responses
- **HTML Pages**: Standard Laravel Blade templates
- **JSON**: For API endpoints like async generation
- **PDF**: Binary PDF files for download endpoints

### Error Responses

#### Validation Errors (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "email": ["The email must be a valid email address."]
  }
}
```

#### Not Found (404)
```json
{
  "message": "No query results for model [App\\Models\\User] {id}"
}
```

#### Server Error (500)
```json
{
  "message": "Server Error"
}
```

## 📝 Data Models

### User Model
```json
{
  "id": "integer",
  "name": "string",
  "email": "string|null",
  "about": "string",
  "photo": "string|null",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### Education Model
```json
{
  "id": "integer",
  "degree": "string",
  "institution": "string", 
  "year": "string"
}
```

### Experience Model
```json
{
  "id": "integer",
  "position": "string",
  "company": "string",
  "start_date": "string",
  "end_date": "string|null",
  "description": "string|null"
}
```

### Skill Model
```json
{
  "id": "integer",
  "skill_name": "string",
  "type": "string" // 'technical' or 'soft'
}
```

## 🔧 Rate Limiting

Currently no rate limiting is implemented. Consider adding rate limiting for production use:

```php
// In routes/web.php
Route::middleware('throttle:60,1')->group(function () {
    // Your routes here
});
```

## 📈 Caching

The application uses intelligent caching:
- **Skills data**: Cached for 1 hour
- **User CV data**: Cached for 1 hour per user
- **Cache driver**: File-based (configurable to Redis)

### Cache Keys:
- `technical_skills` - Technical skills list
- `soft_skills` - Soft skills list  
- `user_cv_data_{user_id}` - Complete user CV data

## 🧪 Testing Examples

### cURL Examples

#### Generate CV Preview:
```bash
curl -X POST http://localhost:8000/generate \
  -F "name=John Doe" \
  -F "about=Software Developer" \
  -F "photo=@/path/to/photo.jpg" \
  -F "education[0][degree]=Bachelor of Science" \
  -F "education[0][institution]=University of Technology" \
  -F "education[0][year]=2020"
```

#### Download PDF:
```bash
curl -X POST http://localhost:8000/download-pdf \
  -d "name=John Doe" \
  -d "about=Software Developer" \
  --output cv.pdf
```

### JavaScript Fetch Example:
```javascript
const formData = new FormData();
formData.append('name', 'John Doe');
formData.append('about', 'Software Developer');
formData.append('education[0][degree]', 'Bachelor of Science');

fetch('/generate', {
  method: 'POST',
  body: formData,
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  }
})
.then(response => response.text())
.then(html => {
  // Handle HTML response
});
```

## 🚀 Performance Tips

1. **Use caching**: Enable Redis for better performance
2. **Optimize images**: Compress uploaded photos
3. **Queue jobs**: Use async PDF generation for better UX
4. **CDN**: Serve static assets via CDN in production
5. **Database indexing**: Add indexes on frequently queried fields