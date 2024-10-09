-- admin_administrator definition
CREATE TABLE `admin_administrator`
(
    `id`          int                                                           NOT NULL AUTO_INCREMENT COMMENT '主键',
    `username`    varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
    `password`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码（此字段数据较为特殊,请查阅加密算法文档）',
    `role_id`     int                                                           NOT NULL COMMENT '角色组ID',
    `status`      tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
    `create_time` int                                                           NOT NULL COMMENT '创建时间',
    `update_time` int                                                           NOT NULL COMMENT '更新时间',
    `delete_time` int DEFAULT NULL COMMENT '软删除，删除时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='后台管理员表';

INSERT INTO cms_hyeprf.admin_administrator
(username,
 password,
 role_id,
 status,
 create_time,
 update_time,
 delete_time)
VALUES ('admin',
        '$2y$10$ib54iCkNk90wGspCqaV7pOwvLaCTN2jEHHg2Oq3o3S4O7txPZo4je',
        0,
        1,
        1724673485,
        1725341944,
        NULL);