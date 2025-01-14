@extends('layouts.app')

@section('content')
    <style>
        /* Layout Template Editor */
        .template-a4 {
            width: 21cm;
            /* Lebar kertas A4 */
            height: 29.7cm;
            /* Tinggi kertas A4 */
            padding: 2cm;
            /* Margin dalam */
            background-color: white;
            /* Warna latar kertas */
            border: 1px solid #ccc;
            /* Border untuk tampilan kertas */
            overflow: auto;
            /* Scroll jika konten melebihi area */
            position: relative;
            /* Untuk mengatur elemen posisi absolut */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Bayangan */
        }

        /* Styling Field yang di-drop */
        .template-a4 .dropped-field {
            position: absolute;
            cursor: move;
            /* Tampilkan ikon seret */
            padding: 5px 10px;
            background-color: #f8f9fa;
            border: 1px dashed #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
        }
    </style>
    <div class="container">
        <h1>Drag-and-Drop Fields</h1>
        <div class="row">
            <!-- Available Fields -->
            <div class="col-md-6">
                <h3>Available Fields</h3>
                <ul id="available-fields" class="list-group">
                    @foreach ($fields as $field)
                        <li class="list-group-item" data-tag="{{ $field->tag }}">
                            {{ $field->name }} ({{ $field->tag }})
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Template Editor -->
            <div class="col-md-6">
                <h3>Template Editor</h3>
                <ul id="template-editor" class="list-group">
                    <li class="list-group-item">Drop fields here</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>x
        // Template Editor
        const templateEditor = document.getElementById('template-editor');
        const availableFields = document.getElementById('available-fields');

        // Drag-and-Drop
        new Sortable(availableFields, {
            group: 'shared',
            animation: 150,
        });

        // Custom Drag-and-Drop to Position Fields
        templateEditor.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        availableFields.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text', e.target.dataset.tag);
        });

        templateEditor.addEventListener('drop', (e) => {
            const tag = e.dataTransfer.getData('text');
            const newField = document.createElement('div');
            newField.classList.add('dropped-field');
            newField.textContent = tag;
            newField.style.left = `${e.offsetX}px`;
            newField.style.top = `${e.offsetY}px`;
            templateEditor.appendChild(newField);

            // Enable dragging within editor
            newField.setAttribute('draggable', true);
            newField.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', JSON.stringify({
                    left: e.target.style.left,
                    top: e.target.style.top
                }));
            });
        });
    </script>
@endsection
