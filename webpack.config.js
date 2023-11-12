/*
 * Copyright (c) 2023. noorakram.com
 *
 *
 */
module.exports = {
    module: {
        rules: [
            {
                test: /\.blade\.php$/,
                use: [
                    {
                        loader: 'webpack-blade-native-loader',
                        options: {
                            viewDir: 'resources/views'
                        }
                    }
                ],
            },
        ],
    },
};