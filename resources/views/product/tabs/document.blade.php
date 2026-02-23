@foreach($product->documents as $index => $document)
    @php $id = Str::slug($document->documentType->name); @endphp
    @include("widget::product.tabs.documents.{$document->documentType->media_type}")
@endforeach
