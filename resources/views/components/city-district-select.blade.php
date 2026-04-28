<div x-data="cityDistrict('{{ $selectedCity }}', '{{ $selectedDistrict }}')" x-init="init()">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">City / Governorate</label>
        <select
            name="city"
            x-model="selectedCity"
            @change="onCityChange()"
            required
            class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500"
        >
            <option value="">Select city</option>
            <template x-for="city in cities" :key="city">
                <option :value="city" x-text="city" :selected="city === selectedCity"></option>
            </template>
        </select>
    </div>

    <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
        <select
            name="district"
            x-model="selectedDistrict"
            required
            :disabled="!selectedCity"
            class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500 disabled:opacity-40 disabled:cursor-not-allowed"
        >
            <option value="">Select district</option>
            <template x-for="district in districts" :key="district">
                <option :value="district" x-text="district" :selected="district === selectedDistrict"></option>
            </template>
        </select>
        <p x-show="!selectedCity" class="text-xs text-gray-400 mt-1">Select a city first</p>
    </div>

</div>

<script src="/js/egypt-cities.js"></script>
<script>
function cityDistrict(preselectedCity, preselectedDistrict) {
    return {
        cities: Object.keys(EGYPT_CITIES),
        districts: [],
        selectedCity: preselectedCity,
        selectedDistrict: preselectedDistrict,

        init() {
            if (this.selectedCity) {
                this.districts = EGYPT_CITIES[this.selectedCity] || [];
            }
        },

        onCityChange() {
            this.districts = EGYPT_CITIES[this.selectedCity] || [];
            this.selectedDistrict = '';
        }
    }
}
</script>