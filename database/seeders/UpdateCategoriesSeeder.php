<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Lập trình',
                'slug' => 'lap-trinh',
                'description' => 'Khám phá thế giới lập trình với các ngôn ngữ phổ biến như PHP, Python, JavaScript, Java, C++. Từ cơ bản đến nâng cao, học cách xây dựng ứng dụng web, mobile và phần mềm chuyên nghiệp.',
                'image' => 'categories/lap-trinh.jpg'
            ],
            [
                'id' => 2,
                'name' => 'Thiết kế',
                'slug' => 'thiet-ke',
                'description' => 'Học thiết kế đồ họa, UI/UX, và thiết kế web với các công cụ chuyên nghiệp như Adobe Photoshop, Illustrator, Figma, Canva. Phát triển kỹ năng sáng tạo và thẩm mỹ cho sự nghiệp thiết kế.',
                'image' => 'categories/thiet-ke.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Khoa học dữ liệu',
                'slug' => 'khoa-hoc-du-lieu',
                'description' => 'Nắm vững phân tích dữ liệu, thống kê, và visualization với Python, R, SQL, Power BI. Học cách khai thác insights từ dữ liệu lớn và đưa ra quyết định dựa trên số liệu.',
                'image' => 'categories/khoa-hoc-du-lieu.jpg'
            ],
            [
                'id' => 4,
                'name' => 'Trí tuệ nhân tạo',
                'slug' => 'tri-tue-nhan-tao',
                'description' => 'Khám phá Machine Learning, Deep Learning, Computer Vision, NLP và LLM. Xây dựng mô hình AI thực tế với TensorFlow, PyTorch, scikit-learn và các framework hiện đại.',
                'image' => 'categories/tri-tue-nhan-tao.jpg'
            ],
            [
                'id' => 5,
                'name' => 'Phát triển web',
                'slug' => 'phat-trien-web',
                'description' => 'Xây dựng website và ứng dụng web hoàn chỉnh với HTML, CSS, JavaScript, React, Laravel, Node.js. Từ frontend responsive đến backend mạnh mẽ, làm chủ full-stack development.',
                'image' => 'categories/phat-trien-web.jpg'
            ],
            [
                'id' => 6,
                'name' => 'Phát triển di động',
                'slug' => 'phat-trien-di-dong',
                'description' => 'Tạo ứng dụng mobile đa nền tảng với React Native, Flutter, hoặc native iOS/Android. Học cách thiết kế UX mobile, tích hợp API và publish app lên App Store/Google Play.',
                'image' => 'categories/phat-trien-di-dong.jpg'
            ],
            [
                'id' => 7,
                'name' => 'Marketing số',
                'slug' => 'marketing-so',
                'description' => 'Làm chủ Digital Marketing với SEO, SEM, Social Media Marketing, Email Marketing, Google Ads, Facebook Ads. Học cách tăng traffic, chuyển đổi khách hàng và đo lường hiệu quả chiến dịch.',
                'image' => 'categories/marketing-so.jpg'
            ],
            [
                'id' => 8,
                'name' => 'Kinh doanh',
                'slug' => 'kinh-doanh',
                'description' => 'Phát triển kỹ năng quản lý, lãnh đạo, khởi nghiệp và chiến lược kinh doanh. Học cách lập kế hoạch, quản trị tài chính, marketing và vận hành doanh nghiệp hiệu quả.',
                'image' => 'categories/kinh-doanh.jpg'
            ],
            [
                'id' => 9,
                'name' => 'An ninh mạng',
                'slug' => 'an-ninh-mang',
                'description' => 'Bảo vệ hệ thống và dữ liệu với kiến thức về Cybersecurity, Ethical Hacking, Network Security, Penetration Testing. Học cách phát hiện lỗ hổng, phòng chống tấn công và tuân thủ an toàn thông tin.',
                'image' => 'categories/an-ninh-mang.jpg'
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')
                ->where('id', $category['id'])
                ->update([
                    'description' => $category['description'],
                    'image' => $category['image'],
                    'updated_at' => now()
                ]);
        }

        $this->command->info('✅ Đã cập nhật description và image cho tất cả categories!');
    }
}
