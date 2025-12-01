<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Folder ZIP</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Upload Folder ZIP</h4>
        </div>

        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
        <div class="container py-5">

            <!-- Upload Card -->
            <div class="card shadow-lg border-0 mb-5">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-upload"></i> Upload ZIP File
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('post.fileUploader') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select ZIP File</label>
                            <input type="file" name="zipfile" class="form-control" accept=".zip" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-semibold">
                            <i class="bi bi-cloud-arrow-up"></i> Upload ZIP
                        </button>
                    </form>
                </div>
            </div>

            <!-- Uploaded Files Table -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-secondary text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-folder"></i> Uploaded Files</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>File Name</th>
                                <th>Size</th>
                                <th>Extention</th>
                                <th>Uploaded At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($files as $f)
                              <tr>
                                    <td>{{ $f->filename }}</td>
                                    <td>{{ number_format($f->file_size / 1024, 2) }} KB</td>
                                    <td>{{$f->extension}} </td>
                                    <td>{{ $f->last_modified}}</td>
                                    <td class="text-center">
                                        <a href="https:://drive.google.com/file/d/{{$f->drive_file_id}}/view" target="_blank" class="btn btn-primary btn-sm fw-semibold" target="_blank">
                                           View
                                        </a>
                                        <a href="{{route('post.downloadFile', $f->id)}}" class="btn btn-primary btn-sm fw-semibold" >
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                        <a href="{{route('post.deleteFile', $f->id)}}" class="btn btn-primary btn-sm fw-semibold" >
                                            <i class="bi bi-download"></i> Delete
                                        </a>
                                    </td>
                              </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>



        </div>
    </div>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
