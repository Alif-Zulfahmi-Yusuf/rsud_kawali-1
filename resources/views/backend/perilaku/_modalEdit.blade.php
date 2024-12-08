<div class="modal fade" id="editPerilakuModal" tabindex="-1" aria-labelledby="editPerilakuModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPerilakuModalLabel">Edit Perilaku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPerilakuForm" action="#" method="POST">
                    @csrf
                    <input type="hidden" name="uuid" id="edit-uuid">
                    <div class="form-group mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="edit-category" name="category_perilaku_id" required>
                            <option value="" selected disabled>Pilih Category</option>
                            @foreach ($categori as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="perilaku" class="form-label">Perilaku</label>
                        <input type="text" name="name" id="edit-perilaku" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-outline-secondary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>