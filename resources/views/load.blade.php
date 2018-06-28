@extends('layouts.app')
@push('scripts')
<script type="text/javascript">
window.top.location = "{{ $redirect_url }}";
</script>
@endpush
