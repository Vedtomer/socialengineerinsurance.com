<form action="{{ route('reports.user.download') }}" method="POST">
    @csrf
    <input type="hidden" name="role" value="{{ $role }}">
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="{{ $role }}_from_date" class="form-label">
                    <i class="fas fa-calendar-alt me-1"></i> From Date
                </label>
                <input type="date" class="form-control w-100" id="{{ $role }}_from_date" name="from_date">
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="{{ $role }}_to_date" class="form-label">
                    <i class="fas fa-calendar-alt me-1"></i> To Date
                </label>
                <input type="date" class="form-control w-100" id="{{ $role }}_to_date" name="to_date">
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="{{ $role }}_id" class="form-label">
                    <i class="fas fa-{{ $role == 'agent' ? 'user-tie' : 'users' }} me-1"></i> 
                    {{ ucfirst($role) }}
                </label>
                <select class="form-control w-100" id="{{ $role }}_id" name="user_id">
                    <option value="">All {{ ucfirst($role) }}s</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="form-group">
                <label for="{{ $role }}_status" class="form-label">
                    <i class="fas fa-toggle-on me-1"></i> Status
                </label>
                <select class="form-control w-100" id="{{ $role }}_status" name="status">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
        <button type="reset" class="btn btn-outline-secondary me-2">
            <i class="fas fa-undo me-1"></i> Reset Filters
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-file-excel me-1"></i> Download {{ ucfirst($role) }} Report
        </button>
    </div>
</form>