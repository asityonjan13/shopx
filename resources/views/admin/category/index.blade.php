@extends('admin.layouts.app')
@push('styles')
    <style>
        .dd-item.custom-cat-item {
            border: none;
            padding: 0;
            margin-bottom: 0;
            background: none;
            border-radius: 0;
        }

        .dd-item-row.custom-cat-row {
            user-select: text;
            background: none;
            gap: 4px;
            border: 1px solid #e9ecef;
            min-height: 38px;
            display: flex;
            align-items: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            position: relative;
        }

        .dd-handle.custom-cat-handle {
            cursor: move;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            flex-shrink: 0;
        }

        .cat-folder-icon {
            font-size: 16px;
            color: #6c757d;
            flex-shrink: 0;
            margin-right: 0.5rem;
        }

        .cat-label.custom-cat-label {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1 1 auto;
        }

        /* Ensure consistent left alignment for all items */
        .dd-list {
            padding-left: 0;
            margin-bottom: 0;
        }

        /* Nested items get proper indentation */
        .dd-item .dd-list {
            padding-left: 40px;
        }

        /* ===== Fix for Nestable's expand/collapse buttons ===== */
        /* Position the +/- buttons that Nestable adds */
        .dd-item>button[data-action="collapse"],
        .dd-item>button[data-action="expand"] {
            position: absolute;
            left: 0;
            top: 0;
            width: 30px;
            height: 38px;
            margin: 0;
            padding: 0;
            z-index: 3;
        }

        /* Add left padding to items that have expand/collapse buttons */
        .dd-item:not(.dd-collapsed)>.dd-item-row,
        .dd-item.dd-collapsed>.dd-item-row {
            padding-left: 2.5rem;
            /* Space for the +/- button */
        }

        /* Items without children keep normal padding */
        .dd-item.dd-nodrag>.dd-item-row {
            padding-left: 0.75rem;
        }

        /* Optional: Add hover effect for better UX */
        .dd-item-row.custom-cat-row:hover {
            background-color: #f8f9fa;
        }

        /* Style the expand/collapse buttons */
        .dd-item>button[data-action] {
            background: transparent;
            border: none;
            font-size: 18px;
            color: #6c757d;
            cursor: pointer;
        }

        .dd-item>button[data-action]:hover {
            color: #495057;
        }

        /* Hide any default nestable button styling we don't want */
        .dd-item>button[data-action]:before {
            display: show;
        }
    </style>
@endpush
@section('contents')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Categories</span>
                        <button class="btn btn-primary btn-sm" id="btn-new">New</button>
                    </div>
                    <div class="card-body">
                        <div id="category-tree" class="dd">

                        </div>
                        <div id="tree-loading" class="text-center my-2 d-none">
                            <div class="spinner-border"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span id="form-title">Create Category</span>
                        <span></span>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="category-form" novalidate>
                            <div class="mb-2">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required id="name">
                            </div>
                            <div class="mb-2">
                                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" required id="slug">
                            </div>
                            <div class="mb-2">
                                <label for="parent_id" class="form-label">Parent Category</label>
                                <select name="parent_id" class="form-select" id="parent_id">
                                    <option value="">None (Root)</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-check form-switch form-switch-3">
                                    <input class="form-check-input" type="checkbox" checked="" name="is_active"
                                        id="is_active">
                                    <span class="form-check-label">Active</span>
                                </label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
                                <button type="button" class="btn btn-danger" id="btn-delete">Delete</button>
                                <button type="button" class="btn btn-secondary" id="btn-cancel">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let editingId = null;
            // 1.Form submission
            $('#category-form').submit(function(e) {
                e.preventDefault();

                const url = editingId ?
                    "{{ url('admin/categories') }}/" + editingId :
                    "{{ route('admin.categories.store') }}";

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        name: $('#name').val(),
                        slug: $('#slug').val(),
                        parent_id: $('#parent_id').val() || null,
                        is_active: $('#is_active').is(':checked') ? 1 : 0,
                        _token: '{{ csrf_token() }}',
                        _method: editingId ? 'PUT' : 'POST'
                    },
                    success: function(response) {
                        if (response.message) {
                            notyf.success(response.message);
                        }
                        clearForm();
                        loadTree();
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                let firstMessage = Object.values(xhr.responseJSON.errors)[0][0];
                                notyf.error(firstMessage);
                            } else if (xhr.responseJSON.message) {
                                notyf.error(xhr.responseJSON.message);
                            } else {
                                notyf.error('An error occurred');
                            }
                        } else {
                            notyf.error('An error occurred');
                        }
                    }
                });
            });

            // 2.Load Parent Dropdown
            function loadParentDropdown(selectedId, excludeId) {
                $.get("{{ route('admin.categories.nested') }}", function(data) {
                    let options = '<option value="">None (Root)</option>';

                    function addOptions(cats, prefix) {
                        cats.forEach(function(cat) {
                            if (excludeId && cat.id == excludeId) return;
                            options +=
                                `<option value="${cat.id}" ${selectedId == cat.id ? 'selected' : ''}>${prefix}${cat.name}</option>`;
                            if (cat.children_nested && cat.children_nested.length) {
                                addOptions(cat.children_nested, prefix + '—');
                            }
                        });
                    }

                    addOptions(data, '');
                    $('#parent_id').html(options);
                });
            }

            // 3.Load Tree
            function loadTree() {
                $('#tree-loading').removeClass('d-none');

                $.get("{{ route('admin.categories.nested') }}", function(data) {
                    $('#category-tree').empty();

                    if (!data || data.length === 0) {
                        $('#category-tree').html(
                            "<div class='text-muted p-3'>No categories yet. Click \"New\" to create one.</div>"
                        );
                        $('#tree-loading').addClass('d-none');
                    } else {
                        var html = "<ol class='dd-list'>" + renderTree(data) + "</ol>";
                        $('#category-tree').html(html);

                        $('#category-tree').nestable({
                            maxDepth: 4, //
                            group: 1
                        }).off('change').on('change', function(e) {
                            UpdateTreeOrder();
                        });

                        $('#tree-loading').addClass('d-none');
                    }
                }).fail(function() {
                    $('#tree-loading').addClass('d-none');
                    notyf.error('Failed to load categories');
                });
            }

            // 4.Render Tree recursively
            function renderTree(categories) {
                if (!categories || !categories.length) return '';

                let html = '';

                categories.forEach(function(cat) {
                    const hasChildren = cat.children_nested && cat.children_nested.length > 0;

                    html += `<li class="dd-item custom-cat-item" data-id="${cat.id}">
                <div class="dd-item-row custom-cat-row ${!hasChildren ? 'no-children' : ''}">
                    <div class="dd-handle custom-cat-handle" title="Drag to re-order">
                        <i class="ti ti-grip-horizontal"></i>
                    </div>
                    <i class="ti ti-folder cat-folder-icon"></i>
                    <div class="cat-label custom-cat-label" data-category-id="${cat.id}">
                        <span>${cat.name}</span>
                        ${!cat.is_active ? '<span class="text-danger ms-2">Inactive</span>' : '<i class="ti ti-check text-success ms-2"></i>'}
                    </div>
                </div>`;

                    if (hasChildren) {
                        html += '<ol class="dd-list">' + renderTree(cat.children_nested) + '</ol>';
                    }

                    html += '</li>';
                });

                return html;
            }

            // 5.Update Tree Order Function - called when tree structure changes
            function UpdateTreeOrder() {
                const serialized = $('#category-tree').nestable('serialize');

                $.ajax({
                    url: "{{ route('admin.categories.update-order') }}",
                    method: 'POST',
                    data: {
                        order: JSON.stringify(serialized),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        notyf.success('Categories order updated successfully');
                        loadTree(); // Reload to reflect new structure
                    },
                    error: function() {
                        notyf.error('Failed to update-order categories');
                        loadTree(); // Reload to restore original order
                    }
                });
            }

            // 6.Edit Category Function - triggered when clicking on category label
            $(document).on('click', '.cat-label', function() {
                const categoryId = $(this).data('category-id');

                // Load category data for editing
                $.get("{{ url('admin/categories') }}/" + categoryId + "/edit", function(data) {
                    editingId = data.id;
                    $('#name').val(data.name);
                    $('#slug').val(data.slug);
                    $('#is_active').prop('checked', data.is_active == 1);
                    loadParentDropdown(data.parent_id, data.id);
                    $('#btn-delete').show();
                    $('#form-title').text('Edit Category');
                }).fail(function() {
                    notyf.error('Failed to load category');
                });
            });

            // 7.Delete Category Function
            $('#btn-delete').click(function() {
                if (!editingId) return;

                if (confirm('Are you sure you want to delete this category?')) {
                    $.ajax({
                        url: "{{ url('admin/categories') }}/" + editingId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.message) {
                                notyf.success(response.message);
                            }
                            clearForm();
                            loadTree();
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                notyf.error(xhr.responseJSON.message);
                            } else {
                                notyf.error('Failed to delete category');
                            }
                        }
                    });
                }
            });

            // 8.Auto-generate slug from name
            $('#name').on('input', function() {
                if (!editingId) { // Only auto-generate for new categories
                    const slug = $(this).val()
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    $('#slug').val(slug);
                }
            });
            
            // Cancel Button Function
            $('#btn-cancel').click(function() {
                clearForm();
            });

            // New Button Function
            $('#btn-new').click(function() {
                clearForm();
            });

            // Clear Form
            function clearForm() {
                editingId = null;
                $('#category-form')[0].reset();
                $('#is_active').prop('checked', true);
                loadParentDropdown(null, null);
                $('#btn-delete').hide();
                $('#form-title').text('Create Category');
            }
            // Initial Load
            clearForm();
            loadTree();
        });
    </script>
@endpush
