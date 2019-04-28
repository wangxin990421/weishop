<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016100100636151",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEpQIBAAKCAQEAzbgg3cHsyD3/i3LHUSdvHh5CfTN+3DDgu9X0S8PNe1oP8MhZfXjoaqCFeF3dLYQDTWK3dXekCCHQjGPV5ECkaqWCPtMwBSDiQNy9Ai4kpIc0UNsHcImjbBlv52BAq+hJhfIKXJ69nA1CzmL40ygnLFhtSHT47YMAdq7WP9Oe28xsdV4z0O6f9CK1kPfkJJXZJR2ddb/Ws2u/BlwD3YUq1s/BlcGTS3S+jHgVmEbzUARbB1fsqffIjnchCTfqIH8KovHojSQ/hzachjD4i7m+EPo4Tr6aj87AZo+lQHAPvywli2PqQOrp9sYOpdol4LXfVhylxwSRLBiuAky3omK0qwIDAQABAoIBAC/zd1W5WiPir6oleugjtPbkPsNIOY3BpuChomv2m/Lgr06EpLdmc2ZHPFUuK9vsJYjoNbMfzBBJMX9H1hd6Kdjh81YJi+vmGlbcHQJhFxFyonMmWrmUm48a7saT3P81Cc46+MdWCQRS4vuQfkdZ2KexhOeavFtAad9AKZh/D0xUEvHmjfEZaooa0DuqNo5qZ1W2HRhTckz4GDPm338k4ORF3ARVncP0ToFEUbddTTvratlj1Ko3M1qXvBC6tIx5oi2gjyqfYGqNIcvXnvxz4XWPZ5j39Tzf5J9rVUQidTeVTtdee9FgpKW2GkQuBd5PsTeB7V6OvFScQiKqlof6IkECgYEA7ucoWZ4msxJanG7qr72WI20tp5FDwIMe2PWITOeRN3xVj39uIziWIsk+ryFcp8IldVnc6/WGfMHda9K0QN5qJMhLpqukxXkiDVch9OMpMi9vg2udIR4I/zuj96+53xb6ynVWRr1GWgthGV5wBLLqWmMVvbVw9F0W/0QUxJ2Hg6MCgYEA3HEGmLZx7X5IF68MbhuA2oSQyxWca3KwsQrgZODUx4+5AD6nD2Kr490vkDtFpsQW9SYYbe9Mzb8kWulQBi4Rs+09IsqHq8Lo+1vyek5bW2RPKWKAW0P4RcVdsKhhCQGSpb/12wUksiJQyA9v3DIxavQuTrA+GMOZ3n0d/MNJW1kCgYEAhXKaF3VAKMcX1koezgwY0bEqz29VaTPVB1wfxtWTSGsrX/zOiN4S/1tqqGj6WLsaMXMA9M0xUn74MEKCbbnPkLxRhchbQfNoEguZ7DD30hCtObvjCZy9adTmwnRmXzd236CrkNgpdCLnGkSmTfmi7tpqSo4PinB1aCGfeDSGfgECgYEA1cShUfwytMzuGlF9K1Vueflt9BKL8kJxB+51Nj40+STtkUgwdyIHcFuw4RwpahPj820y6YDSqP6/wY1ZNfT848+epptiP3Uuu3opxOMxgonenlJQCLD8FKTVOxe9qZ8l16g0QWVWdYwDbE5T+mOOOpp6QYJ1ZUxhbTlFmDTEcVkCgYEAkpv73aqufJnwBSoqYjVh8g/Au7kNpQGyFnknOjjzPx0gaxmxx8qXocFr0ADhV8DJh7Z0bVMs/svC7JkEKJObQnqZYoDN/5w6nRHUmmzmLE047CtUYBqJ49t+LnZxOxqDwH2BO8KH5HPTebPmb9hMXiO2zCOGlohVFBx9meHafyQ=",
		
		//异步通知地址
		'notify_url' => "http://工程公网访问地址/alipay.trade.wap.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://mitsein.com/alipay.trade.wap.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApkRIxOrFl87Mz5BjXdFDU/NqOziFfTNAMQJnOnB/SGMsuQbWb1kqA0hTAuz4n9TNzQcqdhMtaVsg+tJ+c1TDSPFNjhTHt4IEqogysfVBViPinPstNfOpi6e5czHHEp2Me5WZxfmHhxI1t/+kDTkf2PE1evn02MGc5BTm/CPA2uiqLSqy9XuY+y/fVSCKwRLkob3d4wvqYFAl3baR9npH7LkDq5bPNg+ORqEsg8dSTNT8Xf7AJ6tpupliYRNKx3CTD/VNTpQFdY9MBZpYAHWnm8atju6z5I3QWGIw718OJUSeykKWk4X1PEM1qfedawdQwzUsdeHRf46JeUWGGmZqswIDAQAB",
		
	
);