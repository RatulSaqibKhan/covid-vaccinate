/** @type {import('next').NextConfig} */
import withBundleAnalyzer from '@next/bundle-analyzer';

const enableBundleAnalyzer = withBundleAnalyzer({
    enabled: process.env.ANALYZE === 'true',
});

const nextConfig = {
  async rewrites() {
    return [
      {
        source: `${process.env.NEXT_PUBLIC_BACKEND_SERVICE_BASE_PROXY_URI}/:path*`,
        destination: `${process.env.NEXT_PUBLIC_BACKEND_SERVICE_BASE_URI}/:path*`,
      }
    ];
  },
  crossOrigin: 'anonymous'
};

export default enableBundleAnalyzer(nextConfig);