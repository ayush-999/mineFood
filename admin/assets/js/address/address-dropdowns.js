// address-dropdowns.js
class AddressDropdowns {
  constructor(options = {}) {
    // Default configuration
    const defaults = {
      stateSelector: "#custom_state",
      districtSelector: "#custom_district",
      pincodeSelector: "#custom_pincode",
      citySelector: "#custom_city",
      countrySelector: "#custom_country",
      jsonUrl: "pincode.json",
    };

    // Merge options with defaults
    this.config = { ...defaults, ...options };
    this.pincodeData = [];

    // Initialize
    this.init();
  }

  async init() {
    try {
      // Load pincode data
      this.pincodeData = await this.loadPincodeData();

      if (this.pincodeData.length === 0) {
        console.warn("No pincode data loaded - dropdowns will be disabled");
        this.disableAllDropdowns();
        return;
      }

      // Set up event handlers
      this.setupEventHandlers();

      // Initialize country (fixed to India)
      this.initializeCountry();

      // Populate states
      this.populateStates();
    } catch (error) {
      console.error("Error initializing address dropdowns:", error);
    }
  }

  disableAllDropdowns() {
    $(this.config.stateSelector).prop("disabled", true);
    $(this.config.districtSelector).prop("disabled", true);
    $(this.config.pincodeSelector).prop("disabled", true);
    $(this.config.citySelector).prop("disabled", true);
  }

  async loadPincodeData() {
    try {
      const response = await fetch(this.config.jsonUrl);
      if (!response.ok) {
        throw new Error(
          `Failed to load pincode data from ${this.config.jsonUrl}`
        );
      }
      const data = await response.json();
      if (!data.records) {
        throw new Error("Invalid pincode data format - missing records array");
      }
      return data.records;
    } catch (error) {
      console.error("Error loading pincode data:", error);
      // Return empty array to prevent further errors
      return [];
    }
  }

  initializeCountry() {
    const countrySelect = document.querySelector(this.config.countrySelector);
    if (countrySelect) {
      countrySelect.innerHTML = '<option value="India" selected>India</option>';
    }
  }

  populateStates() {
    const stateSelect = document.querySelector(this.config.stateSelector);
    if (!stateSelect) return;

    // Get unique states
    const states = [
      ...new Set(this.pincodeData.map((item) => item.statename)),
    ].sort();

    // Populate dropdown
    stateSelect.innerHTML = '<option value="">Select State</option>';
    states.forEach((state) => {
      stateSelect.innerHTML += `<option value="${state}">${state}</option>`;
    });
  }

  setupEventHandlers() {
    // State change handler
    $(this.config.stateSelector).change(() => this.onStateChange());

    // District change handler
    $(this.config.districtSelector).change(() => this.onDistrictChange());

    // Pincode change handler
    $(this.config.pincodeSelector).change(() => this.onPincodeChange());
  }

  onStateChange() {
    const selectedState = $(this.config.stateSelector).val();
    const districtSelect = $(this.config.districtSelector);
    const pincodeSelect = $(this.config.pincodeSelector);
    const citySelect = $(this.config.citySelector);

    if (selectedState) {
      // Filter districts for selected state
      const districts = [
        ...new Set(
          this.pincodeData
            .filter((item) => item.statename === selectedState)
            .map((item) => item.district)
        ),
      ].sort();

      // Populate districts dropdown
      districtSelect
        .empty()
        .append('<option value="">Select District</option>');
      districts.forEach((district) => {
        districtSelect.append(
          `<option value="${district}">${district}</option>`
        );
      });

      districtSelect.prop("disabled", false);
      pincodeSelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select Pincode</option>');
      citySelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select City</option>');
    } else {
      districtSelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select District</option>');
      pincodeSelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select Pincode</option>');
      citySelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select City</option>');
    }
  }

  onDistrictChange() {
    const selectedState = $(this.config.stateSelector).val();
    const selectedDistrict = $(this.config.districtSelector).val();
    const pincodeSelect = $(this.config.pincodeSelector);
    const citySelect = $(this.config.citySelector);

    if (selectedState && selectedDistrict) {
      // Filter pincodes for selected state and district
      const pincodes = [
        ...new Set(
          this.pincodeData
            .filter(
              (item) =>
                item.statename === selectedState &&
                item.district === selectedDistrict
            )
            .map((item) => item.pincode)
        ),
      ].sort();

      // Populate pincodes dropdown
      pincodeSelect.empty().append('<option value="">Select Pincode</option>');
      pincodes.forEach((pincode) => {
        pincodeSelect.append(`<option value="${pincode}">${pincode}</option>`);
      });

      pincodeSelect.prop("disabled", false);
      citySelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select City</option>');
    } else {
      pincodeSelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select Pincode</option>');
      citySelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select City</option>');
    }
  }

  onPincodeChange() {
    const selectedState = $(this.config.stateSelector).val();
    const selectedDistrict = $(this.config.districtSelector).val();
    const selectedPincode = $(this.config.pincodeSelector).val();
    const citySelect = $(this.config.citySelector);

    if (selectedState && selectedDistrict && selectedPincode) {
      // Filter cities for selected pincode
      const cities = [
        ...new Set(
          this.pincodeData
            .filter(
              (item) =>
                item.statename === selectedState &&
                item.district === selectedDistrict &&
                item.pincode === selectedPincode
            )
            .map((item) => {
              // Clean up officename to get city name
              let cityName = item.officename;
              // Remove all known post office suffixes and trim whitespace
              cityName = cityName
                .replace(
                  /\s*(B\.O|S\.O|H\.O|G\.P\.O|P\.O|A\.O|D\.O|BO|SO|HO|GPO|PO|AO|DO)$/i,
                  ""
                )
                .trim();
              return cityName;
            })
        ),
      ].sort();

      // Populate cities dropdown
      citySelect.empty().append('<option value="">Select City</option>');
      cities.forEach((city) => {
        citySelect.append(`<option value="${city}">${city}</option>`);
      });

      citySelect.prop("disabled", false);
    } else {
      citySelect
        .prop("disabled", true)
        .empty()
        .append('<option value="">Select City</option>');
    }
  }
}

// Initialize when DOM is ready
$(document).ready(function () {
  window.addressDropdowns = new AddressDropdowns();
});
