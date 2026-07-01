<div class="card shadow-sm border-0 border-top border-primary border-3 mb-4">
    <div class="card-header bg-white border-0 pt-3">
        <h3 class="card-title font-weight-bold text-dark">
            <i class="fas fa-filter text-primary mr-2"></i> Global Dashboard Filter
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" aria-label="Collapse Filter" title="Tutup Filter">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body bg-light pt-2 pb-3">
        <form action="{{ url()->current() }}" method="GET" id="globalFilterForm">
            <div class="row">
                <!-- Cluster -->
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1" for="filter_cluster_id">Cluster Segmen</label>
                    <select name="cluster_id" id="filter_cluster_id" class="form-control form-control-sm shadow-sm" aria-label="Pilih Cluster Segmen" title="Pilih Cluster Segmen">
                        <option value="">Semua Cluster</option>
                        @foreach($filterOptions['clusters'] as $cluster)
                            <option value="{{ $cluster->id }}" {{ request('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                {{ $cluster->cluster_label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Day Type -->
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1" for="filter_day_type">Day Type</label>
                    <select name="day_type" id="filter_day_type" class="form-control form-control-sm shadow-sm" aria-label="Pilih Tipe Hari" title="Pilih Tipe Hari">
                        <option value="">Semua Tipe Hari</option>
                        @foreach($filterOptions['day_types'] as $dayType)
                            <option value="{{ $dayType }}" {{ request('day_type') == $dayType ? 'selected' : '' }}>
                                {{ $dayType }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Peak Hour -->
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1" for="filter_peak_hour">Kategori Jam</label>
                    <select name="peak_hour" id="filter_peak_hour" class="form-control form-control-sm shadow-sm" aria-label="Pilih Kategori Jam" title="Pilih Kategori Jam">
                        <option value="">Semua Jam</option>
                        @foreach($filterOptions['peak_hours'] as $val => $label)
                            <option value="{{ $val }}" {{ request('peak_hour') != null && request('peak_hour') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Corridor -->
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1" for="filter_corridor_name">Koridor Utama</label>
                    <select name="corridor_name" id="filter_corridor_name" class="form-control form-control-sm shadow-sm" aria-label="Pilih Koridor Utama" title="Pilih Koridor Utama">
                        <option value="">Semua Koridor</option>
                        @foreach($filterOptions['corridors'] as $corridor)
                            <option value="{{ $corridor }}" {{ request('corridor_name') == $corridor ? 'selected' : '' }}>
                                {{ $corridor }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Time Category -->
                <div class="col-md-4 mb-2">
                    <label class="small text-muted mb-1" for="filter_time_category">Kategori Waktu (Time Category)</label>
                    <select name="time_category" id="filter_time_category" class="form-control form-control-sm shadow-sm" aria-label="Pilih Kategori Waktu" title="Pilih Kategori Waktu">
                        <option value="">Semua Waktu</option>
                        @foreach($filterOptions['time_categories'] as $timeCategory)
                            <option value="{{ $timeCategory }}" {{ request('time_category') == $timeCategory ? 'selected' : '' }}>
                                {{ $timeCategory }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Age Group -->
                <div class="col-md-4 mb-2">
                    <label class="small text-muted mb-1" for="filter_age_group">Grup Umur (Age Group)</label>
                    <select name="age_group" id="filter_age_group" class="form-control form-control-sm shadow-sm" aria-label="Pilih Grup Umur" title="Pilih Grup Umur">
                        <option value="">Semua Grup Umur</option>
                        @foreach($filterOptions['age_groups'] as $ageGroup)
                            <option value="{{ $ageGroup }}" {{ request('age_group') == $ageGroup ? 'selected' : '' }}>
                                {{ $ageGroup }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Travel Type -->
                <div class="col-md-4 mb-2">
                    <label class="small text-muted mb-1" for="filter_travel_type">Tipe Perjalanan</label>
                    <select name="travel_type" id="filter_travel_type" class="form-control form-control-sm shadow-sm" aria-label="Pilih Tipe Perjalanan" title="Pilih Tipe Perjalanan">
                        <option value="">Semua Tipe Perjalanan</option>
                        @foreach($filterOptions['travel_types'] as $travelType)
                            <option value="{{ $travelType }}" {{ request('travel_type') == $travelType ? 'selected' : '' }}>
                                {{ $travelType }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary btn-sm px-4 mr-2 shadow-sm" aria-label="Terapkan Filter" title="Terapkan Filter">
                    <i class="fas fa-filter mr-1"></i> Apply Filter
                </button>
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm px-4 shadow-sm" aria-label="Reset Filter" title="Reset Filter">
                    <i class="fas fa-undo mr-1"></i> Reset Filter
                </a>
            </div>
        </form>
    </div>
</div>
