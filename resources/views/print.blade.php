@extends('dashboard.layouts.master')

@section('scripts')
 <script>
     showHistryReward('{{ route('exams.showReward', ['exam' => $exam->id]) }}')
 </script>
@endsection
