<span @class([
    'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
    'bg-amber-100 text-amber-800' => in_array($status, ['Menunggu Pembayaran', 'Menunggu Validasi']),
    'bg-blue-100 text-blue-800' => in_array($status, ['Diproses']),
    'bg-teal-100 text-teal-800' => in_array($status, ['Siap Diantar', 'Selesai']),
    'bg-red-100 text-red-800' => in_array($status, ['Ditolak', 'Dibatalkan']),
    'bg-gray-100 text-gray-700' => !in_array($status, [
        'Menunggu Pembayaran',
        'Menunggu Validasi',
        'Diproses',
        'Siap Diantar',
        'Selesai',
        'Ditolak',
        'Dibatalkan',
    ]),
])>
    {{ $status }}
</span>