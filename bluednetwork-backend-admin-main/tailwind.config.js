const colors = require('tailwindcss/colors');

module.exports = {
    purge: [
        './vendor/ph7jack/wireui/resources/**/*.blade.php',
        './vendor/ph7jack/wireui/ts/**/*.ts',
        './vendor/ph7jack/wireui/src/View/**/*.php',
        './vendor/livewire-ui/modal/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            screens: {
                'print': {'raw': 'print'},
                'dark-mode': {'raw': '(prefers-color-scheme: dark)'},
            },
            colors: {
                'blue-gray': colors.blueGray,
                'cool-gray': colors.coolGray,
                facebook: '#3b5998',
                twitter: '#00aced',
                linkedin: '#007bb6',
                youtube: '#bb0000',
                instagram: '#517fa4',
                google: '#dd4b39',
                pinterest: '#cb2027',
                primary: '#00c696',
                'zoom': '#4A8CFF',
                "smoke-900": "rgba(0,0,0,0.9)",
                "smoke-800": "rgba(0,0,0,0.75)",
                "smoke-600": "rgba(0,0,0,0.6)",
                "smoke": "rgba(0,0,0,0.5)",
                "smoke-400": "rgba(0,0,0,0.4)",
                "smoke-200": "rgba(0,0,0,0.25)",
                "smoke-100": "rgba(0,0,0,0.1)"
            },
            height: {
                "h-82": "21rem",
                "h-84": "22rem",
                "h-86": "23rem",
                "screen/20": "20vh",
                "screen/25": "25vh",
                "screen/30": "30vh",
                "screen/35": "35vh",
                "screen/40": "40vh",
                "screen/41": "41vh",
                "screen/42": "42vh",
                "screen/43": "43vh",
                "screen/44": "44vh",
                "screen/45": "45vh",
                "screen/50": "50vh",
                "screen/55": "55vh",
                "screen/60": "60vh",
                "screen/65": "65vh",
                "screen/70": "70vh",
                "screen/75": "75vh",
                "screen/80": "80vh",
                "screen/85": "85vh",
                "screen/90": "90vh",
                "screen/95": "95vh",
                "screen/3": "calc(100vh / 3)",
                "screen/4": "calc(100vh / 4)",
                "screen/5": "calc(100vh / 5)",
            },
            inset: {
                "23": "5.5rem",
                "29": "7.5rem",
                "35": "8.5rem",
                "39": "9.8rem"

            }
        },
    },
    variants: {
        extend: {
            height: ["responsive", "hover", "focus"],
            fontWeight: ['hover', 'focus'],
            fontVariantNumeric: ['hover', 'focus'],
            cursor: ['responsive', 'hover', 'focus'],
            flex: ['responsive', 'hover', 'focus'],
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        //require('@tailwindcss/typography'),
        require('@tailwindcss/line-clamp'),
        require('postcss-preset-env'),
    ],
}
