@extends('layouts.admin')
@section('title', 'Kategori Produk')
@section('page_title', 'Manajemen Kategori')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Kategori Produk</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Kelola pengelompokan produk E-commerce Anda</p>
    </div>
    <button onclick="document.getElementById('modal-add').style.display='flex'" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> <span>Tambah Kategori</span>
    </button>
</div>

<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; background: var(--bg-card);">
    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: rgba(255,255,255,0.02);">
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Kategori</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Produk</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Status</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr style="transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="display:flex;align-items:center;gap:14px;">
                            @if($cat->image)
                            <img src="{{ asset('storage/'.$cat->image) }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:10px;">
                            @else
                            <div style="width:40px;height:40px;background:var(--bg-main);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--text-muted); border:1px solid var(--border);">
                                <i class="{{ $cat->icon ?? 'fa-solid fa-layer-group' }}" style="font-size:16px;"></i>
                            </div>
                            @endif
                            <div>
                                <div style="font-weight:700; color: var(--text-main); font-size: 15px;">{{ $cat->name }}</div>
                                <div style="font-size:12px;color:var(--text-muted); margin-top: 2px;">{{ Str::limit($cat->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <span style="font-weight:700; color:var(--text-main);">{{ $cat->products_count }}</span> <span style="font-size:12px; color:var(--text-muted);">Produk</span>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        @if($cat->is_active)
                        <span class="badge badge-success">Aktif</span>
                        @else
                        <span class="badge badge-gray">Non-Aktif</span>
                        @endif
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                        <div style="display:flex;gap:8px; justify-content: flex-end;">
                            <button onclick="editCategory({{ json_encode($cat) }})" class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px;">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px; color: var(--danger); border-color: rgba(244,63,94,0.2);">
                                    <i class="fa-solid fa-trash-can" style="font-size: 14px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:64px 24px;color:var(--text-muted);">Belum ada kategori ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add -->
<div id="modal-add" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:20px;">
    <div class="card" style="width:100%; max-width:500px; background:var(--bg-card); border:1px solid var(--border); border-radius:24px; position:relative;">
        <div style="padding:24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:18px; font-weight:800; color:var(--text-main);">Tambah Kategori Baru</h3>
            <button onclick="document.getElementById('modal-add').style.display='none'" style="background:none; border:none; color:var(--text-muted); cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" style="padding:24px;">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Nama Kategori</label>
                <input type="text" name="name" required style="width:100%; padding:12px; background:var(--bg-main); color:var(--text-main); border:1px solid var(--border); border-radius:12px;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Ikon (FontAwesome Class)</label>
                <input type="text" name="icon" placeholder="fa-solid fa-layer-group" style="width:100%; padding:12px; background:var(--bg-main); color:var(--text-main); border:1px solid var(--border); border-radius:12px;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Deskripsi</label>
                <textarea name="description" rows="3" style="width:100%; padding:12px; background:var(--bg-main); color:var(--text-main); border:1px solid var(--border); border-radius:12px;"></textarea>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Gambar Hero (Opsional)</label>
                <input type="file" name="image" style="width:100%; font-size:12px; color:var(--text-muted);">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:14px;">Simpan Kategori</button>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:20px;">
    <div class="card" style="width:100%; max-width:500px; background:var(--bg-card); border:1px solid var(--border); border-radius:24px; position:relative;">
        <div style="padding:24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:18px; font-weight:800; color:var(--text-main);">Edit Kategori</h3>
            <button onclick="document.getElementById('modal-edit').style.display='none'" style="background:none; border:none; color:var(--text-muted); cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-edit" method="POST" enctype="multipart/form-data" style="padding:24px;">
            @csrf @method('PUT')
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Nama Kategori</label>
                <input type="text" name="name" id="edit-name" required style="width:100%; padding:12px; background:var(--bg-main); color:var(--text-main); border:1px solid var(--border); border-radius:12px;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Ikon (FontAwesome Class)</label>
                <input type="text" name="icon" id="edit-icon" style="width:100%; padding:12px; background:var(--bg-main); color:var(--text-main); border:1px solid var(--border); border-radius:12px;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Status</label>
                <select name="is_active" id="edit-status" style="width:100%; padding:12px; background:var(--bg-main); color:var(--text-main); border:1px solid var(--border); border-radius:12px;">
                    <option value="1">Aktif</option>
                    <option value="0">Non-Aktif</option>
                </select>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:8px;">Ganti Gambar</label>
                <input type="file" name="image" style="width:100%; font-size:12px; color:var(--text-muted);">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:14px;">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
function editCategory(cat) {
    document.getElementById('edit-name').value = cat.name;
    document.getElementById('edit-icon').value = cat.icon || '';
    document.getElementById('edit-status').value = cat.is_active;
    document.getElementById('form-edit').action = "{{ route('admin.categories.index') }}/" + cat.id;
    document.getElementById('modal-edit').style.display = 'flex';
}
</script>
@endsection
