module.exports = {
    entry: "./src/Client/index.jsx",
    output: {
        path: __dirname + "/web/dist/",
        filename: "bundle.js"
    },
    module: {
        loaders: [
	{ test: /\.css$/, loader: "style-loader!css-loader" },
	{
            test: /.jsx?$/,
            loader: 'babel-loader',
            exclude: /node_modules/,
            query: {
                presets: ['react']
            }
        }]
    }
};
