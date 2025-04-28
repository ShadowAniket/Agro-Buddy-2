// Soil Map of India - Interactive Map
document.addEventListener('DOMContentLoaded', function() {
    // Soil type data for each state
    const soilData = {
        'Andhra Pradesh': {
            soilType: 'Red soil and black soil',
            description: 'Red soil is rich in iron but poor in nitrogen, while black soil is ideal for cotton cultivation.',
            products: 'Rice, cotton, sugarcane, and fruits like mangoes'
        },
        'Arunachal Pradesh': {
            soilType: 'Mountain soil and forest soil',
            description: 'Rich in organic matter, suitable for horticulture and forestry.',
            products: 'Oranges, apples, ginger, and cardamom'
        },
        'Assam': {
            soilType: 'Alluvial soil',
            description: 'Fertile soil deposited by rivers, rich in minerals.',
            products: 'Tea, rice, jute, and sugarcane'
        },
        'Bihar': {
            soilType: 'Alluvial soil',
            description: 'Highly fertile soil deposited by Ganges river, rich in potash and lime.',
            products: 'Rice, wheat, maize, and pulses'
        },
        'Chhattisgarh': {
            soilType: 'Red and yellow soil',
            description: 'Acidic soil with low fertility, requires proper management.',
            products: 'Rice, maize, and pulses'
        },
        'Goa': {
            soilType: 'Laterite soil',
            description: 'Leached soil with high iron and aluminum content.',
            products: 'Rice, coconut, cashew nuts, and spices'
        },
        'Gujarat': {
            soilType: 'Black soil, alluvial soil, and saline soil',
            description: 'Varied soil types with black soil in central regions and alluvial in river basins.',
            products: 'Cotton, groundnut, wheat, and dates'
        },
        'Haryana': {
            soilType: 'Alluvial soil',
            description: 'Fertile soil with good water retention capacity.',
            products: 'Wheat, rice, sugarcane, and cotton'
        },
        'Himachal Pradesh': {
            soilType: 'Mountain soil',
            description: 'Shallow soil with high organic content, suitable for fruit cultivation.',
            products: 'Apples, plums, cherries, and potatoes'
        },
        'Jharkhand': {
            soilType: 'Red soil and laterite soil',
            description: 'Acidic soil with low fertility, requires proper management.',
            products: 'Rice, maize, pulses, and vegetables'
        },
        'Karnataka': {
            soilType: 'Red soil, black soil, and laterite soil',
            description: 'Varied soil types with red soil in southern regions and black soil in northern parts.',
            products: 'Coffee, silk, sugarcane, and millets'
        },
        'Kerala': {
            soilType: 'Laterite soil',
            description: 'Acidic soil rich in iron and aluminum, suitable for plantation crops.',
            products: 'Coconut, rubber, spices, and tea'
        },
        'Madhya Pradesh': {
            soilType: 'Black soil and red soil',
            description: 'Black soil retains moisture, ideal for cotton; red soil suitable for cereals.',
            products: 'Wheat, soybeans, cotton, and pulses'
        },
        'Maharashtra': {
            soilType: 'Black soil and laterite soil',
            description: 'Black soil retains moisture, ideal for cotton.',
            products: 'Cotton, sugarcane, and fruits like oranges'
        },
        'Manipur': {
            soilType: 'Mountain soil',
            description: 'Rich in organic matter, suitable for horticulture.',
            products: 'Rice, fruits, and vegetables'
        },
        'Meghalaya': {
            soilType: 'Laterite soil',
            description: 'Acidic soil with high iron content, suitable for plantation crops.',
            products: 'Rice, potatoes, and fruits'
        },
        'Mizoram': {
            soilType: 'Mountain soil',
            description: 'Rich in organic matter, suitable for horticulture.',
            products: 'Rice, maize, and vegetables'
        },
        'Nagaland': {
            soilType: 'Mountain soil and forest soil',
            description: 'Rich in organic matter, suitable for horticulture and forestry.',
            products: 'Rice, millets, and vegetables'
        },
        'Odisha': {
            soilType: 'Red soil and alluvial soil',
            description: 'Red soil in uplands and alluvial soil in coastal areas.',
            products: 'Rice, pulses, oilseeds, and jute'
        },
        'Punjab': {
            soilType: 'Alluvial soil',
            description: 'Highly fertile soil with good water retention capacity.',
            products: 'Wheat, rice, cotton, and sugarcane'
        },
        'Rajasthan': {
            soilType: 'Desert and sandy soil',
            description: 'Low fertility soil with poor water retention, requires irrigation.',
            products: 'Bajra, pulses, and oilseeds'
        },
        'Sikkim': {
            soilType: 'Mountain soil',
            description: 'Rich in organic matter, suitable for horticulture.',
            products: 'Cardamom, ginger, and fruits'
        },
        'Tamil Nadu': {
            soilType: 'Red soil, black soil, and alluvial soil',
            description: 'Varied soil types with red soil in southern regions and alluvial in river basins.',
            products: 'Rice, sugarcane, cotton, and bananas'
        },
        'Telangana': {
            soilType: 'Red soil and black soil',
            description: 'Red soil is rich in iron but poor in nitrogen, while black soil is ideal for cotton cultivation.',
            products: 'Rice, cotton, and pulses'
        },
        'Tripura': {
            soilType: 'Mountain soil',
            description: 'Rich in organic matter, suitable for horticulture.',
            products: 'Rice, jute, and fruits'
        },
        'Uttar Pradesh': {
            soilType: 'Alluvial soil',
            description: 'Highly fertile soil deposited by rivers, rich in minerals.',
            products: 'Wheat, rice, sugarcane, and potatoes'
        },
        'Uttarakhand': {
            soilType: 'Mountain soil and forest soil',
            description: 'Rich in organic matter, suitable for horticulture and forestry.',
            products: 'Rice, wheat, and fruits'
        },
        'West Bengal': {
            soilType: 'Alluvial soil',
            description: 'Highly fertile soil deposited by rivers, rich in minerals.',
            products: 'Rice, jute, tea, and vegetables'
        },
        'Andaman and Nicobar Islands': {
            soilType: 'Coastal and alluvial soil',
            description: 'Soil influenced by coastal conditions, suitable for plantation crops.',
            products: 'Coconut, spices, and tropical fruits'
        },
        'Chandigarh': {
            soilType: 'Alluvial soil',
            description: 'Fertile soil with good water retention capacity.',
            products: 'Wheat, rice, and vegetables'
        },
        'Dadra and Nagar Haveli': {
            soilType: 'Coastal alluvial soil',
            description: 'Soil influenced by coastal conditions, suitable for plantation crops.',
            products: 'Rice, fruits, and vegetables'
        },
        'Daman and Diu': {
            soilType: 'Coastal alluvial soil',
            description: 'Soil influenced by coastal conditions, suitable for plantation crops.',
            products: 'Coconut, rice, and vegetables'
        },
        'Delhi': {
            soilType: 'Alluvial soil',
            description: 'Fertile soil deposited by Yamuna river.',
            products: 'Vegetables and flowers'
        },
        'Jammu and Kashmir': {
            soilType: 'Mountain soil and forest soil',
            description: 'Rich in organic matter, suitable for horticulture and forestry.',
            products: 'Apples, walnuts, saffron, and rice'
        },
        'Ladakh': {
            soilType: 'Mountain soil',
            description: 'Arid soil with low fertility, requires irrigation.',
            products: 'Barley, wheat, and apricots'
        },
        'Lakshadweep': {
            soilType: 'Coral and sandy soil',
            description: 'Soil influenced by coral formations, suitable for coconut cultivation.',
            products: 'Coconut and fish'
        },
        'Puducherry': {
            soilType: 'Coastal alluvial soil',
            description: 'Soil influenced by coastal conditions, suitable for plantation crops.',
            products: 'Rice, sugarcane, and coconut'
        }
    };

    // Color mapping for soil types
    const soilColors = {
        'Alluvial soil': '#386CB0',
        'Black soil and laterite soil': '#7FC97F',
        'Black soil and red soil': '#F002C7',
        'Black soil, alluvial soil, and saline soil': '#BF5B17',
        'Coastal alluvial soil': '#BEAED4',
        'Coastal and alluvial soil': '#666666',
        'Coral and sandy soil': '#18A1CD',
        'Desert and sandy soil': '#9ABFF3',
        'Forest soil': '#FDC086',
        'Laterite soil': '#FFFF99',
        'Mountain soil': '#C1008E',
        'Mountain soil and forest soil': '#EF6E3D',
        'Red and laterite soil': '#E4E4E4',
        'Red and yellow soil': '#FE4AC0',
        'Red soil and alluvial soil': '#68030B',
        'Red soil and black soil': '#F002C7',
        'Red soil, black soil, and alluvial soil': '#00DCA6',
        'Red soil, black soil, and laterite soil': '#FFCBD3',
        'Sandy loam and coastal alluvial soil': '#AA7CEF',
        'Sandy soil': '#FA8C00'
    };

    // Create image map areas dynamically
    function createImageMap() {
        const img = document.querySelector('.map-container img');
        if (!img) return;

        // Wait for image to load to get dimensions
        img.onload = function() {
            const width = this.width;
            const height = this.height;

            // Add event listeners to the image
            img.addEventListener('mousemove', function(e) {
                // Get mouse position relative to image
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                // Determine which state based on position (simplified example)
                // In a real implementation, you would need precise coordinates for each state
                let state = '';

                // Example: Maharashtra is in the center-west
                if (x > width * 0.4 && x < width * 0.6 && y > height * 0.5 && y < height * 0.7) {
                    state = 'Maharashtra';
                }
                // Example: Uttar Pradesh is in the north-central
                else if (x > width * 0.3 && x < width * 0.5 && y > height * 0.2 && y < height * 0.4) {
                    state = 'Uttar Pradesh';
                }
                // Example: Rajasthan is in the northwest
                else if (x > width * 0.1 && x < width * 0.3 && y > height * 0.2 && y < height * 0.4) {
                    state = 'Rajasthan';
                }

                // Show soil info if we're hovering over a known state
                if (state && soilData[state]) {
                    showSoilInfo(state, soilData[state].soilType, soilData[state].description, soilData[state].products);
                } else {
                    hideSoilInfo();
                }
            });

            // Hide info when mouse leaves the image
            img.addEventListener('mouseleave', hideSoilInfo);
        };
    }

    // Initialize the map
    createImageMap();

    // Function to show soil information
    window.showSoilInfo = function(state, soilType, description, products) {
        const infoBox = document.getElementById('soil-info-box');
        if (!infoBox) return;

        infoBox.innerHTML = `
            <h4>${state}</h4>
            <p><strong>Soil Type:</strong> ${soilType}</p>
            <p><strong>Description of Soil:</strong> ${description}</p>
            <p><strong>Products:</strong> ${products}</p>
        `;
        infoBox.style.display = 'block';
    };

    // Function to hide soil information
    window.hideSoilInfo = function() {
        const infoBox = document.getElementById('soil-info-box');
        if (infoBox) {
            infoBox.style.display = 'none';
        }
    };
});
