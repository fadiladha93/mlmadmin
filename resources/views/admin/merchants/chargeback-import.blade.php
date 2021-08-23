@extends('admin.layouts.main')
@section('main_content')
<div class="m-content">

    <!-- @php
        if(isset($errors)){
            echo 'Errors';
            var_dump($errors);
        }

        
        if(isset($headerLine)){
            echo 'Header Line';
            var_dump($headerLine);
        }

        if(isset($numHeaders)){
            echo 'Num Headers';
            var_dump($numHeaders);
        }

        if(isset($headers)){
            echo 'Headers';
            var_dump($headers);
        }

        if(isset($numRequiredHeaders)){
            echo 'Num Required Headers';
            var_dump($numRequiredHeaders);
        }
        
        if(isset($activeFields)){
            echo 'Active Fields';
            var_dump($activeFields);
        }

        if(isset($missingHeaders)){
            echo 'Missing Headers';
            var_dump($missingHeaders);
        }

        if(isset($actualHeaders)){
            echo 'Actual Headers';
            var_dump($actualHeaders);
        }

        if(isset($success)){
            echo 'Success';
            var_dump($success);
        }
    @endphp -->
    <div class="row">
        <div class="col-md-12">
            @if (isset($results))
            @endif
            
                @if (isset($errors) && is_array($errors))
                    
                @endif
                <div class="chargeback">
                    <chargeback></chargeback>
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection
@section('last_scripts')

@endsection
<script>
    import ImportReformatModal from "../../../default/js/components/ImportReformatModal";
    export default {
        components: {ImportReformatModal}
    }
</script>


@section('scripts')
    <script src="{{asset('/js/app.js')}}" type="text/javascript"></script>
@endsection
